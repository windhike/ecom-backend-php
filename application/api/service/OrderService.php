<?php
/**
 * Author: mark m /
 * Date:4/27/2020 11:40 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;


use app\api\model\DeliverRecord as DeliveryRecordModel;
use app\api\model\Order;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\User;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\LogisticsException;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;
use think\Request;

class OrderService
{
    protected $productsFromOrder; //产品订单信息，即从客户端传来的products参数
/*    $productsFromOrder 的二维数组结构如下：
            [
                [
                    'product_id' => 1,
                    'count'=>2,
                ],
                [
                    'product_id' => 3,
                    'count'=>6,
                    ]
            ]
*/
    protected $productsInfo; //真实的产品信息（服务端存储的信息），包括库存信息
    protected $uid;

/*    placeOrder后返回给客户端的OrderStatus结构如下：
    return $orderStatus [
    'inStock'=>true/false,
    'order_no'=>$orderNo,
    'order_id'=>$orderId,
    'create_time'=>$createTime,
    ];*/

    public function place($uid, $productsFromOrder){
        $this->productsFromOrder=$productsFromOrder;
        $this->uid=$uid;
        $this->productsInfo= $this->getProductsByOrder($productsFromOrder);
        $orderStatus=$this->getOrderStatus();
        if (!$orderStatus['inStock']){
            $orderStatus['order_id']=-1;
            //如果inStock，则会开始创建订单，每个创建的订单都要记录一个order_id。同样对于库存不足的order_id=-1
            return $orderStatus; //out of  stock 直接返回orderStatus with order_id=-1;
        }
        else{
            //创建订单；创建订单最主要的是要存储当时订单的快照/snap，包括当时的price，address等等
            $orderSnap = $this->createOrderSnap($orderStatus);
            $order=$this->createOrder($orderSnap);
            $order['inStock']=true;
            return $order;
        }

    }


    private  function createOrder($snap){
        Db::startTrans(); // 事务的起点； 一个事务是整体执行的，中间出差的化会进行回滚 rollback
        try {  // 对于比较复杂的业务逻辑，可以加入try catch，捕捉运行时出现的通用异常，并throw出来，有利于定位问题。
               //并且用try catch 可以配合事务进行rollback
                $orderNo = self::makeOrderNo();
                $order = new Order();
                $order->order_no = $orderNo;
                $order->user_id = $snap['user_id'];
                $order->total_price = $snap['total_price'];
                $order->snap_img = $snap['snap_img'];
                $order->snap_name = $snap['snap_name'];
                $order->total_count = $snap['total_count'];
                $order->snap_address = $snap['snap_address'];
                $order->snap_items = json_encode($snap['pStatus']); // 数组写入数据库要json encode
                $order->status = OrderStatusEnum::UNPAID; //订单执行状态： 1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足
                $order->save();

                $orderId=$order->id;

                foreach ($this->productsFromOrder as &$oProduct){  //&$oProduct ? 要加个&符号才能对数组里的字段修改？
                    $oProduct['order_id'] = $orderId;
                }

                (new OrderProduct())->saveAll($this->productsFromOrder);

                $createTime=$order->create_time; //要返回订单创建-即下单时间； 需要在CRM过程中自动生成
                Db::commit(); // 事务的终点；
                return [
                    'order_no'=>$orderNo,
                    'order_id'=>$orderId,
                    'create_time'=>$createTime,
                ];
        }
        catch (Exception $ex){
            Db::rollback(); // 事务出现异常，进行回滚
            throw $ex;
        }

    }

    private function createOrderSnap($orderStatus){
        $snap = [
            'user_id' =>$this->uid,
            'total_price'=> $orderStatus['orderPriceSum'],
            'snap_img'=>$this->productsInfo[0]['main_img_url'], //get snap img by first product id
            'snap_name'=>$this->productsInfo[0]['name'], //取第一个商品的名字
            'total_count'=>$orderStatus['orderCount'],
            'snap_address'=> json_encode($this->getUserAddress()), //因为要存入数据库，所以还要把地址数组转换成json字符串

            'pStatus'=>$orderStatus['pStatusArray'],

        ];

        if(count($this->productsInfo) > 1){
            $snap['snap_name'].= '等';
        }
// 与源码不一致
//        for ($i = 0; $i < count($this->productsInfo); $i++) {
//            $product = $this->productsInfo[$i];
//            $oProduct = $this->productsFromOrder[$i];
//            $pStatus = $this->snapProduct($product, $oProduct['count']);
////            $snap['total_price'] += $pStatus['productPriceSum'];
////            $snap['total_count'] += $pStatus['count'];
////            array_push($snap['pStatus'], $pStatus);
//        }

        return $snap;
    }

    // 单个商品库存检测
    private function snapProduct($product, $oCount)
    {
        $pStatus = [
            'id' => null,
            'name' => null,
            'main_img_url'=>null,
            'count' => $oCount,
            'productPriceSum' => 0,
            'price' => 0
        ];

        $pStatus['count'] = $oCount;
        // 以服务器价格为准，生成订单
        $pStatus['productPriceSum'] = $oCount * $product['price'];
        $pStatus['name'] = $product['name'];
        $pStatus['id'] = $product['id'];
        $pStatus['main_img_url'] =$product['main_img_url'];
        $pStatus['price'] = $product['price'];
        return $pStatus;
    }

    private function getUserAddress(){
        $userAddress = (new User())->addressOfUser($this->uid);
        if(!$userAddress){
            throw new UserException(['msg'=>'用户地址不能为空']);
        }
        else{
            return $userAddress->toArray();
        }
    }

    private function getOrderStatus(){
        $oStatus=[
            'inStock'=>true,
            'orderPriceSum'=>0,
            'orderCount'=>0,
            'pStatusArray'=>[],
            'payStatus'=>null, //后续订单管理有用
        ];
        foreach ($this->productsFromOrder as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->productsInfo);
            if(!$pStatus['inStock']){
                $oStatus['inStock'] = false;
            }
            $oStatus['orderPriceSum'] += $pStatus['productPriceSum'];
            $oStatus['orderCount'] += $pStatus['count'];
            $oStatus['pStatusArray'][] = $pStatus; //$oStatus['pStatusArray'] = array_push($oStatus['pStatusArray'],$pStatus);

        }
        return $oStatus;
    }

    private function getProductStatus($oPId,$oCount,$productsInfo){
        $pStatus=[
            'id'=>null,
            'inStock'=>false,
            'count'=>0,
            'name'=>'',
            'productPriceSum' =>0,
            'stock'=>0,
            'price'=>0,
            'main_img_url'=>'',
        ];
        $pIndex = -1;
        for($i=0;$i<count($this->productsInfo);$i++){
            if($this->productsInfo[$i]['id']==$oPId){
                $pIndex=$i;
            }
        }
        if($pIndex == -1){ //means 订单里的product在数据库中没找到；
            throw new OrderException([
                'msg'=>'ID为'.$oPId.'的商品不存在，创建订单失败',
                    ]);
            }
        else{
            $product=$productsInfo[$pIndex];
            $pStatus=[
                'id'=>$product['id'],
                'inStock'=>false,
                'count'=> $oCount,
                'name'=>$product['name'],
                'productPriceSum' => $product['price']*$oCount,
                'stock'=>$product['stock'],
                'price'=>$product['price'],
                'main_img_url'=>$product['main_img_url'],
                ];
            if ($oCount<=$product['stock']){
                $pStatus['inStock'] = true;
            }

            return $pStatus;
        }

    }

    private function getProductsByOrder($productsFromOrder){
       /* foreach ($productsFromOrder as $productFO){
            $productsId=$productFO['product_id'];
            //然后用productId去查数据库，得到每一个productsInfo;
            //这种方式的问题是出现了循环查数据库的情况，这是需要避免的，因为不知道要循环查多少次，一个order可能有上百个product，容易搞塌服务器
        }*/
       $oPId=[];
        foreach ($productsFromOrder as $productFO){
            $oPId[]=$productFO['product_id'];
        }
        $productsInfo = (new Product())->visible(['id','price','stock','name','main_img_url'])->select($oPId)->toArray();

        return $productsInfo;

    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2020] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    public function checkOrderStock($orderId){
        $this->productsFromOrder = (new OrderProduct())->where('order_id','=',$orderId)->select();
        $this->productsInfo = $this->getProductsByOrder($this->productsFromOrder);

        return $this->getOrderStatus();
    }

    public function updateOrderStatusAfterPay($orderId){
        $orderStatus = $this->checkOrderStock($orderId); //update 前先检查库存
        if($orderStatus['inStock']){
            $newStatus = OrderStatusEnum::PAID;
        }
        else{
            $newStatus = OrderStatusEnum::PAID_BUT_OUT_STOCK;
        }

        (new Order())->where('id','=',$orderId)->update(['status'=>$newStatus]);
        $orderStatus['payStatus'] = $newStatus;
        return $orderStatus;
    }


        /*$pStatus=[
        'id'=>$product['id'],
        'inStock'=>false,
        'count'=> $oCount,
        'name'=>$product['name'],
        'productPriceSum' => $product['price']*$oCount,
        ];*/
    public function reduceStockByOrderId($orderId){
        $orderStatus = $this->checkOrderStock($orderId); //先获得oStatus，里面有pStatusArray;
        foreach ($orderStatus['pStatusArray'] as $singlePStatus){
            (new Product())->where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
        }
        return true;
    }

    public function delivery($orderID, $jumpPage = '')
    {
        $order = OrderModel::where('id', '=', $orderID)
            ->find();
        if (!$order) {
            throw new OrderException();
        }
        if ($order->status != OrderStatusEnum::PAID) {
            throw new OrderException([
                'msg' => '还没付款呢，或者你已经更新过订单了，不要再刷了',
                'errorCode' => 80002,
                'code' => 403
            ]);
        }
        $order->status = OrderStatusEnum::DELIVERED;
        $order->save();
//            ->update(['status' => OrderStatusEnum::DELIVERED]);
        $message = new DeliveryMessage();
        return $message->sendDeliveryMessage($order, $jumpPage);
    }


    /**
     * @param string $company 快递公司编码
     * @param string $number 快递单号
     * @param $orderId
     * @return bool
     * @throws OrderException
     */
    public function deliverGoods($company, $number,$orderId)
    {
        $order = OrderModel::get($orderId);
        if (!$order) Throw new OrderException(['code' => 404, 'msg' => '指定的订单不存在']);
        // 判断订单的状态是否是已支付或者已支付但库存不足的状态
        if ($order->status !== OrderStatusEnum::PAID && $order->status !== OrderStatusEnum::PAID_BUT_OUT_OF) {
            Throw new OrderException(['msg' => '当前订单不允许发货，请检查订单状态', 'errorCode' => '70008']);
        }
        // 启动事务
        Db::startTrans();
        try {
            // 创建一条发货单记录
            DeliverRecord::create([
                'order_no' => $order->order_no,
                'comp' => $company,
                'number' => $number,
                'operator' => AppToken::getCurrentTokenVar('uid'), //=app_id = ac（用户账号名）
            ]);
            // 改变订单状态
            $order->status = OrderStatusEnum::DELIVERED;
            // 调用模型sava()方法更新记录
            $order->save();
            // 提交事务
            Db::commit();
            return true;
        } catch (Exception $ex) {
            // 回滚事务
            Db::rollback();
            throw new OrderException(['msg' => '订单发货不成功', 'errorCode' => '70009']);
        }
    }



}
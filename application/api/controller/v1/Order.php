<?php
/**
 * Author: mark m /
 * Date:4/27/2020 4:14 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\DeliverRecord;
use app\api\model\User;
use app\api\service\BaseToken;
use app\api\service\BaseToken as TokenService;
use app\api\service\OrderService;
use app\api\service\WxPay;
use app\api\validate\IsIdPositiveInt;
use app\api\validate\OrderForm;
use app\api\validate\OrderPlaceValidate;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;
use think\Controller;
use think\Hook;
use think\Request;
use think\Paginator;

class Order extends BaseController
{
    //用户在选择产品后，客户端向API提交相关产品信息；
    //API收到信息后检查该产品的库存情况；
    //有库存，将订单数据存入数据库（状态是下单成功，但不会扣除库存），后返回客户端信息，可以开始支付；
    // ---------------  本文件处理以上步骤 ------------------------------
    //客户调用支付接口，进行支付；
    //服务端需要再次检测库存量，
    //服务端向微信服务端提交预订单请求，查看“微信支付-开发文档-统一下单API接口”，需要用微信提供的SDK
    //从微信服务端得到支付参数
    //服务端将支付参数返回微信小程序
    //微信小程序用支付参数直接向微信服务端支付
    //微信分别返回支付结果给小程序和服务端（服务器不能直接返回支付结果给小程序--微信服务端自己会返回，我们的服务器端只操作库存）
    //（并且微信不是实时返回的，而是异步返回， 所以服务端还要做第三次库存校验）
    //如果支付成功，第三次检测库存量，如果成功，刷新订单数据状态为支付成功，此时才真正扣除库存；
    //如果支付失败，内部进行支付失败处理；
    //----------------------------------------------
    //整个过程中如果无库存，返回客户无库存信息；

    //管理员应该没有placeOrder权限，只有用户有；但管理员有deleteOrder权限而用户没有；
    protected $beforeActionList=[
        'appUserOnlyScope'=>['only'=>'placeOrder'],
        'appAndSuperUserScope'=>['only'=>'getOrderDetail,getSummaryByUser'],
    ];

    public function getSummaryByUser($page=0,$statusList=[0,1],$size=15){
        //user get order summary, may too much, so need to be separated to pages, and each page has a size;
        //uid will be transfer by user token;
        (new PagingParameter())->goCheck();

        $uid = (new BaseToken())->getUidByToken();

        $pagingInstance = (new OrderModel());
        $pagingOrders = $pagingInstance::orderModelGetSummaryByUser($uid,$page,$statusList,$size);
        if($pagingOrders->isEmpty()){ //对于类，要用isEmpty来判空
            return [
                'data' => [],
                'current_page'=>$pagingOrders->currentPage(),
//                'current_page'=>$pagingOrders::getCurrentPage(),
            ];
        }
        $data = $pagingOrders->hidden(['prepay_id','snap_items','snap_address'])->toArray()['data']; //hidden?
        return [
            'data'=>$data,
            'current_page'=>$pagingOrders->currentPage(),
//            'current_page'=>$pagingOrders::getCurrentPage(),
        ];
    }

    public function placeOrder(){
        (new OrderPlaceValidate())->goCheck();

//        $productsFromOrder=input('post.products/a');//input() == Request::post('products/a'); '/a' means transfer to array;
        $productsFormOrder = Request::instance()->post('products/a');// '/a' means transfer to array;
        $uid = (new TokenService())->getUidByToken();

        $order = (new OrderService());
        $orderStatus= $order->place($uid,$productsFormOrder);
        return $orderStatus;

    }

    public function getOrderDetail($id){ // $orderId
        (new IsIdPositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id); //id order model 的主键值，这样就可以查询出来了
        if (!$orderDetail)
        {
            throw new OrderException();
        }

        // 在存DB时，不能存json对象，所以要用json_encode转成字符串存DB；但返回调用时，不应该返回字符串，而应该是json对象，所以json_decode；
        //但以下的方式并不好，因为每个要转换的地方都要写这个代码，比较好的方式是直接在Order类里面转换，采用获取器 getSnapItemsAttr()...
        //在Order类里面设置了获取器后，只要从DB里读到了相关的属性，就会自动执行获取器对其value进行转换。
//        $orderDetail['snap_address']= json_decode($orderDetail['snap_address']);
//        $orderDetail['snap_items']= json_decode($orderDetail['snap_items']);

        return $orderDetail
            ->hidden(['prepay_id']);            ;
    }

    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page=1, $size = 20){
        (new PagingParameter())->goCheck();
//        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        if ($pagingOrders->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray()['data'];
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }

    public function delivery($id){  //id means order_id
        (new IsIdPositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id); //没有输入JumpPage参数，需要的化可以跳转页面
        if($success){
            return new SuccessMessage();
        }
    }

    /**
     * 分页查询所有订单记录
     * @validate('OrderForm')
     */
    public function getOrders()
    {
        $params = Request::instance()->get();
        (new OrderForm())->goCheck();
        $orders = OrderModel::getOrdersPaginate($params);
        if ($orders['total_nums'] === 0) {
            throw new OrderException([
                'code' => 404,
                'msg' => '未查询到相关订单',
                'errorCode' => '70007'
            ]);
        }
        return $orders;
    }

    /**
     * 订单发货
     * @auth('订单发货','订单管理')
     * @param('id','订单id','require|number')
     * @param('comp','快递公司编码','require|alpha')
     * @param('number','快递单号','require|alphaNum')
     */
    public function deliverGoods($id)
    {
        $params = Request::instance()->post();
        // $orderService = new OrderService($id);
        // $result = $orderService->deliverGoods($params['comp'], $params['number']);
        // 简写为
        $result = (new OrderService())->deliverGoods($params['comp'], $params['number'],$id);

        return writeJson(201, $result, '发货成功');
    }

    /**
     * @param $orderNo
     */
    public function getOrderPayStatus($orderNo)
    {
        $result = (new WxPay($orderNo))->config('wx')->getWxOrderStatus();
        return $result;
    }

    /**
     * @param $orderNo
     */
    public function getSecondOrderPayStatus($orderNo)
    {
        $result = (new WxPay($orderNo))->config('second_wx')->getWxOrderStatus();
        return $result;
    }

    /**
     * 订单退款
     * @auth('订单退款','财务管理')
     * @params('order_no','订单号','require')
     * @params('refund_fee','退款金额','require|float|>:0')
     */
    public function refund()
    {
        $params = Request::instance()->post();
        $result = (new WxPay($params['order_no']))->refund($params['refund_fee']);

        $hookParams = "操作订单{$params['order_no']}退款,退款金额{$params['refund_fee']}";
        Hook::listen('logger', $hookParams);
        return $result;
    }

    /**
     * 订单退款查询
     * @param $orderNo
     */
    public function refundQuery($orderNo)
    {
        $result = (new WxPay($orderNo))->refundQuery();
        return $result;
    }

    /**
     * 分页查询订单发货记录
     * @validate('DeliverRecordForm')
     */
    public function getOrderDeliverRecord()
    {
        $params = Request::instance()->get();
        $result = DeliverRecord::getDeliverRecordPaginate($params);
        if ($result['total_nums'] === 0) {
            throw new OrderException([
                'code' => 404,
                'msg' => '未查询到相关发货记录',
                'errorCode' => '70010'
            ]);
        }
        return $result;
    }

}
<?php
/**
 * Author: mark m /
 * Date:4/29/2020 12:44 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


use think\Db;

class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time','update_time'];
    protected $autoWriteTimestamp = true;
    // 告诉模型这个字段是json格式的数据
    protected $json = ['snap_address', 'snap_items'];
    // 设置json数据返回时以数组格式返回
    protected $jsonAssoc = true;

    private $user_id;
    private $total_price;
    private $order_no;
    private $snap_img;
    private $snap_name;
    private $total_count;
    private $snap_address;
    /**
     * @var false|string
     */
    private $snap_items;
    /**
     * @var int
     */
    private $status;

    public static function orderModelGetSummaryByUser($uid,$page=1,$statusList=[0,1],$size=15){ //$statusList单纯为[1]时，搜索不到'in'，不知为何？
//        $result = (new Order)->where('user_id','=',$uid)->order('create_time','desc')->hidden(['prepay_id'])->select();
        $result = (new Order)->where('user_id','=',$uid)->where('status','in',$statusList)->order('create_time','desc')->paginate($size,true,['page'=>$page]);
        //$simple 简洁模式：实战不需要知道总共有多少页，当前在第几页，比如下拉刷新的场景；我们这里可以用简洁模式。paginate 返回的是paginate类
        return $result;
    }
    public function getSnapItemsAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public static function getSummaryByPage($page=1, $size=20){
        $pagingData = self::order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData ;
    }

    public function products()
    {
        return $this->belongsToMany('Product', 'order_product', 'product_id', 'order_id');
    }

    public static function getOrdersPaginate($params)
    {
        $order_no_filter = [ 'like','%'];
        if (array_key_exists('order_no',$params)) {
            $order_no_filter[0] = '=';
            $order_no_filter[1] = $params['order_no'];
        }

        $dateRange = $params['start'].','.$params['end'];
        // paginate()方法用于根据url中的参数，计算查询要查询的开始位置和查询数量
        list($start, $count) = paginate();
        // 应用条件查询
            $orderList = (new Order())->where('create_time', 'between time', $dateRange)
                ->where('order_no', $order_no_filter[0], $order_no_filter[1])
                ->limit($start, $count)
                ->order('create_time desc')
                ->select();

            if (array_key_exists('name',$params)){
                foreach ( $orderList as $k=>$order) {
                    if ((json_decode($order->data['snap_address'])->name) != $params['name']){
                        unset($orderList[$k]);
                    };
                }
            }


        // 调用模型的实例方法count计算该条件下会有多少条记录
        $totalNums = $orderList->count();
        // 调用模型的limit方法对记录进行分页并获取查询结果

        // 组装返回结果
        $result = [
            'collection' => $orderList,
            'total_nums' => $totalNums
        ];

        return $result;
    }

    /**
     * 指定时间范围统计订单基础数据
     */
    public static function getOrderStatisticsByDate($params,$format)
    {

        $dateRange = $params['start'].','.$params['end'];
        $order = (new Order)->where('create_time', 'between time', $dateRange ) // 查询时间范围
            ->where('status', 'between', '2, 4') // 查询status为2到4这个范围的记录// 2（已支付）,3（已发货）,4（已支付但缺货）
            // 格式化create_time字段；做聚合查询
            ->field("FROM_UNIXTIME(create_time,'{$format}') as date,
                    count(*) as count,sum(total_price) as total_price")
            // 查询结果按date字段分组，注意这里因为在field()中给create_time字段起了别名date，所以用date
            ->group("date")
            ->select();

        return $order;
    }

}
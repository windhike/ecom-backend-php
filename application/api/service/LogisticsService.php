<?php
/**
 * Author: mark m /
 * Date:5/19/2020 3:30 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;


use app\api\model\DeliverRecord as DeliveryRecordModel;
use app\lib\exception\LogisticsException;
use app\lib\exception\OrderException;

class LogisticsService
{
    public static function queryLogistics($orderNo)
    {
        $deliverRecord = (new DeliveryRecordModel())->where('order_no', $orderNo)->find();
        if (!$deliverRecord) {
            Throw new OrderException(['msg' => '未查询到指定订单号发货单记录', 'errorCode' => 70011]);
        }
        // 查询缓存中是否有该快递单号的快递信息
        $cache = cache($deliverRecord->comp . $deliverRecord->number);
        // 如果有，直接返回缓存中的信息
        if ($cache) return $cache;
        // 如果不存在，调用第三方扩展进行快递查询
        // 获取第三方扩展需要的配置信息
//        $config = config('logistics.config');
        // 获取快递编码对应公司名称
//        $comp = config('logistics.comp')[$deliverRecord->comp];
        // 实例化第三方扩展类并调用query查询方法，第一个参数是快递单号，第二个参数是快递公司名称(可选，但推荐传递)

            $logisticsOrder = (new LogisticsService())->logisticsQuery($deliverRecord->number, $deliverRecord->comp);
            $logisticsOrder = json_decode($logisticsOrder);
            if($logisticsOrder->error_code!=0 || !$logisticsOrder->result) {
                //错误查询
                throw new LogisticsException(['msg'=>$logisticsOrder]);
            }
            else{
                // 查询成功后把查询结果缓存起来，保留1200秒，即20分钟，这个缓存的过期时间可以按自己需要设置
                cache($deliverRecord->comp . $deliverRecord->number, $logisticsOrder->result, 1200);
                return $logisticsOrder->result;// 返回查询结果
            }




    }

    public function logisticsQuery($number,$comp){
        $provider = sprintf(config('logistics.config')['provider']);
        $appKey= sprintf(config('logistics.config')[$provider]['app_key']);
        $url=sprintf(config('logistics.config')[$provider]['api_url'],$appKey,$number,$comp);
        $result=curl_get($url);
        return $result;
    }
}
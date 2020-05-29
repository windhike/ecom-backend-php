<?php
/**
 * Author: mark m /
 * Date:5/19/2020 12:08 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\service\LogisticsService;
use app\api\validate\OrderNoValidate;

class Logistics extends BaseController
{
    /**
     * 查询订单物流状态
     * @param('orderNo','订单号','require|length:16|alphaNum')
     */
    public function getLogistics($orderNo)
    {
        (new OrderNoValidate())->goCheck();
        $result = LogisticsService::queryLogistics($orderNo);
        return json($result);
    }
}
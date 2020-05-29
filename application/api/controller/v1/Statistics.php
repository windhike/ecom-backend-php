<?php
/**
 * Author: mark m /
 * Date:5/20/2020 12:04 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\service\StatisticsService;
use app\api\validate\DateRangeTypeValidate;
use app\api\validate\DateRangeValidate;
use app\lib\exception\AnalysisException;
use think\Request;
use \app\api\model\Order as OrderModel;

class Statistics extends BaseController
{
    /**
     * 指定时间范围统计订单基础数据
     * @param('start','开始时间','require|date')
     * @param('end','结束时间','require|date')
     * @param('type','日期间距类型','require')
     */
    public function getOrderBaseStatistics()
    {
        $params = Request::instance()->get();
        (new DateRangeTypeValidate())->goCheck();
        $result = StatisticsService::getOrderStatisticsByDate($params);
        return $result;
    }

    /**
     * 获取会员数据基础统计
     * @param('start','开始时间','require|date')
     * @param('end','结束时间','require|date')
     * @return array
     */
    public function getUserBaseStatistics()
    {
        $params = Request::instance()->get();
        (new DateRangeValidate())->goCheck();
        $result = StatisticsService::getUserStatisticsByDate($params);
        return $result;
    }
}
<?php
/**
 * Author: mark m /
 * Date:5/19/2020 8:50 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;

// 注意这里的引用路径是以TP5框架入口文件所在目录算起的，即项目根目录\public\index.php
use app\api\model\Order;
use app\api\model\Order as OrderModel;
use app\lib\exception\PayException;
use think\Config;
use think\Loader;
use WxPayConfig;

//require_once "../extend/wx_pay/WxPay.Api.php";
Loader::import('WxPaySdk.WxPay',EXTEND_PATH,'.Api.php');

class WxPay
{
    private $orderNo;
    private $config;


    public function __construct($orderNo)
    {
        $this->orderNo = $orderNo;
        $this->config = new WxPayConfig('wx');
    }

    public function getWxOrderStatus()
    {
        // 生成查询参数对象
        $inputObj = $this->generateOrderQuery();
        // 调用微信支付订单查询接口
        try {
            $payStatus = \WxPayApi::orderQuery($this->config, $inputObj);
            if ($payStatus['result_code'] === 'FAIL') {
                throw new PayException(['msg' => $payStatus['err_code_des']]);
            }
            $result = [
                'trade_state' => $payStatus['trade_state'],
                'trade_state_desc' => $payStatus['trade_state_desc'],
                'out_trade_no' => $payStatus['out_trade_no'],
                'transaction_id' => $payStatus['transaction_id'] ?? '',
                'is_subscribe' => $payStatus['is_subscribe'] ?? '',
                'total_fee' => $payStatus['total_fee'],
                'cash_fee' => $payStatus['cash_fee'] ?? '',
                'time_end' => $payStatus['time_end'] ?? '',
                'attach' => $payStatus['attach'] ?? '',
            ];
            return $result;
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }
    }

    /**
     * 生成微信支付订单查询参数对象
     */
    protected function generateOrderQuery()
    {
        // 实例化订单查询输入对象
        $inputObj = new \WxPayOrderQuery();
        // 设置商户订单号，用于查询条件
        $inputObj->SetOut_trade_no($this->orderNo);
        return $inputObj;
    }

    /**
     * 设置微信支付商户配置对象
     * @param $name 配置文件文件名
     * @return WxPay
     */
    public function config($name)
    {
        $this->config = new WxPayConfig($name);
        return $this; // 这样可以链式调用；
//        return $this->config = new WxPayConfig($name); // 这样不能链式调用
    }

    public function refund($refundFee)
    {
        try {
            // 数据库中查询订单，因为只需要知道订单的订单总金额字段，在查询时指定了要列出的字段，节省性能
            $order = (new Order())->field('total_price')->where('order_no', $this->orderNo)
                ->find();
            // total_price通过查询数据库订单记录获得，refundFee由外部或者前端传递
            $inputObject = $this->generateRefundObject($order->total_price, $refundFee);
            $refundRes = \WxPayApi::refund($this->config, $inputObject);

            if ($refundRes['return_code'] === 'FAIL') {
                throw new PayException(['msg' => $refundRes['return_msg']]);
            }

            if ($refundRes['result_code'] === 'FAIL') {
                throw new PayException(['msg' => $refundRes['err_code_des']]);
            }
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }

        // 这里提取一些关键的字段内容，可根据自己业务实际情况调整
        $result = [
            'result_code' => $refundRes['return_code'],
            'out_trade_no' => $refundRes['out_trade_no'],
            'out_refund_no' => $refundRes['out_refund_no'],
            'total_fee' => $refundRes['total_fee'],
            'refund_fee' => $refundRes['refund_fee'],
        ];

        return $result;
    }


    /**
     * 生成微信支付退款提交对象
     * @param $totalFee 订单总金额
     * @param $refundFee 退款金额
     */
    protected function generateRefundObject($totalFee, $refundFee)
    {
        $inputObject = new \WxPayRefund();
        // 设置要退款的商户订单号
        $inputObject->SetOut_trade_no($this->orderNo);
        // 设置退款订单号
        // 一笔微信支付订单是可以分开多次退款的，所以需要为每次退款都生成一个订单号作为退款订单号
        // 同一个退款订单号发起多次退款，不会进行多次退款。
        // 这里调用一个我们自己实现的方法用于生成退款订单号
        $inputObject->SetOut_refund_no($this->makeOrderNo());
        // 设置订单总金额，需与原支付订单总金额一致
        // 微信支付接口接收的金额单位是分为单位，所以我们要*100把元化成分
        $inputObject->SetTotal_fee($totalFee * 100);
        // 设置本次退款金额，单位为分
        $inputObject->SetRefund_fee($refundFee * 100);
        // 设置操作人信息，默认传微信支付商户的merchanId即可
        $inputObject->SetOp_user_id($this->config->GetMerchantId());
        // 返回封装好的对象
        return $inputObject;
    }

    /**
     * 生成订单号
     * @return string
     */
    public function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 退款详情查询
     */
    public function refundQuery()
    {
        try {
            $inputObject = $this->generateRefundQueryObject();
            $result = \WxPayApi::refundQuery($this->config, $inputObject);

            if ($result['return_code'] === 'FAIL') {
                throw new PayException(['msg' => $result['return_msg']]);
            }

            if ($result['result_code'] === 'FAIL') {
                throw new PayException(['msg' => $result['err_code_des']]);
            }
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }
        return $result;
    }

    /**
     * 生成微信支付退款详情查询参数对象
     */
    protected function generateRefundQueryObject()
    {
        $inputObject = new \WxPayRefundQuery();
        $inputObject->SetOut_trade_no($this->orderNo);
        return $inputObject;
    }
}
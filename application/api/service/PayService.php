<?php
/**
 * Author: mark m /
 * Date:4/29/2020 9:27 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;


use app\api\model\Order;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPaySdk.WxPay',EXTEND_PATH,'.Api.php');

class PayService
{
    //客户调用支付接口，进行支付；（输入token和order_id）
    //服务端需要再次检测库存量，
    //服务端向微信服务端提交预订单请求，查看“微信支付-开发文档-统一下单API接口”，需要用微信提供的SDK
    //从微信服务端得到支付参数
    //服务端将支付参数返回微信小程序

    private $orderId;
    private $orderNo;
    private $wxConfig;

    function __construct($orderId)
    {
        if(!$orderId){
            throw new Exception('订单id不能为空');
        }
        else{
            $this->orderId = $orderId;
        }
        $this->wxConfig = new \WxPayConfig('wx');
    }

    public function pay(){
        //订单号不存在
        //订单号存在，但和当前用户不匹配
        //订单已被支付
        //以上的这些检测可以不放到validate中去，validate检测的是和业务关系不大的基本规则，比如id是否是正整数。而以上验证时业务验证
        $this->checkOrderValid();  // will return orderNo in function.

        //进行订单库存量检测
        $orderStatus = (new OrderService())->checkOrderStock($this->orderId);

/*        $orderStatus=[
            'inStock'=>true,
            'orderPriceSum'=>0,
            'orderCount'=>0,
            'pStatusArray'=>[],
        ];*/

        if (!$orderStatus['inStock']){
            return $orderStatus; //out of  stock 直接返回orderStatus
        }
        else{
            //开始prepay流程
            return $this->makeWxPreOrder($orderStatus['orderPriceSum']);
        }
    }


    private function makeWxPreOrder($orderPriceSum){
        //向微信申请时，用户标识要用openId标识
        $openId = (new BaseToken())->getOpenIdByToken();
        if(!$openId){
            throw new TokenException();
        }
        $wxUnifiedOrderData = new \WxPayUnifiedOrder();  //没有命名空间的类，前面要加\
        $wxUnifiedOrderData->SetOut_trade_no($this->orderNo);
        $wxUnifiedOrderData->SetTrade_type('JSAPI');
        $wxUnifiedOrderData->SetTotal_fee($orderPriceSum*100); //以‘分’为单位，不是元
        $wxUnifiedOrderData->SetBody('零食商贩');
        $wxUnifiedOrderData->SetOpenid($openId);
        $wxUnifiedOrderData->SetNotify_url(config('secure.wx_notify_url')); //微信服务端异步返回支付是否成功给这个 Notify url

        return $this->getPaySignature($wxUnifiedOrderData);
    }

    private $wxOrderTest = [ //打桩用
        'return_code'=>'SUCCESS',
        'return_msg'=>'appid和mch_id不匹配', //optional or not
        'appid'=>'wx8888888888888888',
        'mch_id'=>'1900000109',
        'device_info'=>'013467007045764',//optional
        'nonce_str'=>'5K8264ILTKCH16CQ2502SI8ZNMTM67VS',
        'sign'=>'C380BEC2BFD727A4B6845133519F3AD6',
        'result_code'=>'SUCCESS',
        'err_code'=>'SYSTEMERROR',//optional
        'err_code_des'=>'系统错误',//optional
        'trade_type'=>'JSAPI',
        'prepay_id'=>'wx201410272009395522657a690389285100',
        'code_url'=>'weixin://wxpay/bizpayurl/up?pr=NwY5Mz9&groupid=00',//optional

    ];

    private function getPaySignature($wxUnifiedOrderData){
        $wxOrder = \WxPayApi::unifiedOrder($this->wxConfig,$wxUnifiedOrderData);
        //需要商户号才能成功！下面为打桩：--------------
        $wxOrder=$this->wxOrderTest;
        //----------------------------------
        if (($wxOrder['return_code']!='SUCCESS') || ($wxOrder['result_code']!='SUCCESS')){
            return $wxOrder; //失败则返回失败结果
        }
        //prepay_id 处理
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;

    }

    private function recordPreOrder($wxOrder){
        (new OrderModel())->where('id','=',$this->orderId)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    private function sign($wxOrder){
        $jsApiPayData=new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());

        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);

//        $jsApiPayData->SetSignType('md5');
        $signVar=$jsApiPayData->MakeSign($this->wxConfig);

        $rawValues=$jsApiPayData->GetValues();//对象转array
        $rawValues['paySign']=$signVar;
        unset($rawValues['appId']); // rawValues 有 appId参数，最好不要返回给客户端，所以删除
        return $rawValues;
    }

    private function checkOrderValid(){
        //订单号不存在
        $result = (new OrderModel())->where('id','=',$this->orderId)->find();
        if (!$result){
            throw new OrderException();
        }
        //订单号存在，但和当前用户不匹配
        // check current Uid with an challengeUid,这里就是用订单数据库里的user_id去challenge 当前url申请的uid（用token查出）；

        if(!(new BaseToken())->checkUidByChallenge($result->user_id)){
            throw new TokenException(
                ['msg'=>'订单用户和当前用户不匹配',
                'errorCode'=>10003,
                ]);
        }
        //订单已被支付
        if($result->status!=OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg'=>'当前订单已提交过支付申请',
                'errorCode'=>80003,
                'code'=>400,
                ]);
        }
        $this->orderNo = $result->order_no;
        return true;
    }

}
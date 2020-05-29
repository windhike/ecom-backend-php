<?php
/**
 * Author: mark m /
 * Date:5/1/2020 12:57 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

//require_once 'C:\Server\xampp\htdocs\my-ecom-backend\extend\WxPaySdk\WxPay.Api.php';
Loader::import('WxPaySdk.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify
{

        /*<xml>
        <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
        <attach><![CDATA[支付测试]]></attach>
        <bank_type><![CDATA[CFT]]></bank_type>
        <fee_type><![CDATA[CNY]]></fee_type>
        <is_subscribe><![CDATA[Y]]></is_subscribe>
        <mch_id><![CDATA[10000100]]></mch_id>
        <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
        <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>

        <result_code><![CDATA[SUCCESS]]></result_code>
        <return_code><![CDATA[SUCCESS]]></return_code>
        <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
        <time_end><![CDATA[20140903131540]]></time_end>
        <total_fee>1</total_fee>
        <coupon_fee><![CDATA[10]]></coupon_fee>
        <coupon_count><![CDATA[1]]></coupon_count>
        <coupon_type><![CDATA[CASH]]></coupon_type>
        <coupon_id><![CDATA[10000]]></coupon_id>
        <coupon_fee><![CDATA[100]]></coupon_fee>
        <trade_type><![CDATA[JSAPI]]></trade_type>
        <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
        //transactionId 这是微信订单号，为支付系统的订单号，由支付系统（微信服务端）生成，并在回调时传回给商户，用于回调，也可查询订单状态
        <out_trade_no><![CDATA[1409811653]]></out_trade_no> //这是商户订单号，是后端服务器生成的
        //out_trade_no 为商户平台的订单号，一般在商户平台生成，自己可以设计自己的规则，如通过时分秒等生成随机数订单  一般不重复 
        //商户提交后支付时把这个订单号传给第三方支付平台（微信服务端），在支付成功后，第三方把这个订单号传回来，我们通过这个订单号进行商户系统的其它操作。如更改数据库支付状态，增加减少商品等
        </xml>*/
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        //TODO 1、进行参数校验
        if(!array_key_exists("return_code", $data)
            ||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            //TODO失败,不是支付成功的通知
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            $msg = "异常异常";
            return false;
        }
        if(!array_key_exists("transaction_id", $data) || !array_key_exists('out_trade_no',$data)){ //无论支付result_code是否成功，这个id是必选的；不过out_trade_no也是必选的，所以我们这里只判断out_trade_no也行
            $msg = "输入参数不正确";
            return false;
        }
        if($data['result_code'] == 'SUCCESS'){ // 如果支付成功
            $orderNo = $data['out_trade_no'];
            Db::startTrans();  // 在收到第一个notify时，为了防止在update状态之前又收到第二个notify，导致多次执行下面的代码出现多次扣库存的情况
            try {  //why try? -- 因为要根据操作是否出error，决定如何应答微信服务端。//如果处理都成功处理，我们返回微信服务端成功处理消息，则微信服务端就不会再继续发notify；
                $orderItem = (new OrderModel())->where('order_no', '=', $orderNo)->lock(true)->find();
                if($orderItem->status ==OrderStatusEnum::UNPAID){
                    //2）更新订单status，更新前先检查库存，确定更新状态为：‘已支付’,或者‘超卖’；
                    $orderStatus = (new OrderService())->updateOrderStatusAfterPay($orderItem->id);
                    //该方法return 一个 update了‘payStatus’之后的$orderStatus：
                    /*$orderStatus=[
                        'inStock'=>true, //可能是true也可能是false
                        'orderPriceSum'=>0,
                        'orderCount'=>0,
                        'pStatusArray'=>[],
                        'payStatus'=>null, //属性该状态PAID or PAID_OUT_OF_STOCK；
                    ];*/
                    //3）//如果没超卖，则减少库存；如果超卖，则只改payStatus，后续再处理；
                    if ($orderStatus['inStock']){
                        (new OrderService())->reduceStockByOrderId($orderItem->id);
                    }

                }

                Db::commit();
                return true;  // 这个return true是告诉微信服务端，我处理完它的notify了，可以停发了。

                //这里没有处理 $orderItem->status ！=OrderStatusEnum::UNPAID的情况，
                //是因为这种情况可能是正常的：因为在微信服务器在确认状态前可能多次发notify，所以出现这种情况不做任何事,直接return true就行
            }
            catch (Exception $ex){
                Db::rollback();
                Log::error($ex);
                return false;
            }
        }
        else{

            return true;//这里是微信notify的‘result_code’ 不是success，这里也应该返回true，表示我已经收到了这notify，微信服务端可以停发了。

        }

    }
}

       /* //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
                Log::ERROR("签名错误...");
                return false;
            }
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
        }

        //TODO 3、处理业务逻辑
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();


        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        return true;

    }*/

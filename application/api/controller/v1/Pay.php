<?php
/**
 * Author: mark m /
 * Date:4/29/2020 6:15 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\service\PayService;
use app\api\service\WxNotify;
use app\api\validate\IsIdPositiveInt;
use think\Controller;

class Pay extends BaseController
{
    //用户在选择产品后，客户端向API提交相关产品信息；
    //API收到信息后检查该产品的库存情况；
    //有库存，将订单数据存入数据库（状态是下单成功，但不会扣除库存），后返回客户端信息，可以开始支付；
    // ---------------  Order Controller处理以上步骤 ------------------------------
    //客户调用支付接口，进行支付；
    //服务端需要再次检测库存量，
    //服务端向微信服务端提交预订单请求，查看“微信支付-开发文档-统一下单API接口”，需要用微信提供的SDK
    //从微信服务端得到支付参数
    //服务端将支付参数返回微信小程序
    //------------------ 本文件处理以上步骤--------------------------------
    //微信小程序用支付参数直接向微信服务端
    //微信分别返回支付结果给小程序和服务端（服务器不能直接返回支付结果给小程序--微信服务端自己会返回，我们的服务器端只操作库存）
    //（并且微信不是实时返回的，而是异步返回， 所以服务端还要做第三次库存校验）
    //如果支付成功，第三次检测库存量，如果成功，刷新订单数据状态为支付成功，此时才真正扣除库存；
    //如果支付失败，内部进行支付失败处理；
    //----------------------------------------------
    //整个过程中如果无库存，返回客户无库存信息；

    //本文件详细步骤如下：

    protected $beforeActionList=[
        'appUserOnlyScope'=>['only'=>'getPreOrder'],
    ];

    public function getPreOrder($id=''){  //需要从用户端得到order_id, 用户端还要携带token
        (new IsIdPositiveInt())->goCheck();
        $payInstance = new PayService($id);
        return $payInstance->pay();
    }

    public function receiveNotify(){
        //1）再次检测库存，防止出现超卖
        //2）更新订单status --》已支付
        //3）在数据库中减少库存
        //如果3步都成功处理，我们返回微信服务端成功处理消息，则微信服务端就不会再继续发notify；
        //否则，要返回处理失败给微信服务端，这样微信服务端会间隔为15s/15s/30s/3m/10m/20m/30m/30m/30m/60m/3h/3h/3h/6h/6h - 总计 24h4m

        //notify的特点：1）用post，2)xml格式，3）不会/也不允许在url用？后面携带参数；
        $notify = new WxNotify();
        $wxConfig = new \WxPayConfig('wx');
        $notify->Handle($wxConfig); // 不能直接调用notifyProcess，因为没有处理参数，而是调用父类的Handle，Handle自己会调用notifyProcess

        //为支持断点跟踪调试而设计的方法：由于微信notify不能携带？参数，也就不能携带?XDEBUG_SESSION_START=17770，所以不能断点调试；
        //本方法是接收到微信的notify后，将notify在本地转发一次到另一个url，在转发过程中携带？xdebug..参数，这样就可以跟踪了。
        //参考10-34章节
//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http:/z.cn/api/v1/pay/re_notify?XDEBUG_SESSION_START=17770',
//            $xmlData);

    }

    public function redirectNotify(){
        //为支持断点跟踪调试而设计的方法：由于微信notify不能携带？参数，也就不能携带?XDEBUG_SESSION_START=17770，所以不能断点调试；
        //本方法是接收到微信的notify后，将notify在本地转发一次到另一个url，在转发过程中携带？xdebug..参数，这样就可以跟踪了。
        $notify = new WxNotify();
        $wxConfig = new \WxPayConfig('wx');
        $notify->Handle($wxConfig); // 不能直接调用notifyProcess，因为没有处理参数，而是调用父类的Handle，Handle自己会调用notifyProcess
    }
}
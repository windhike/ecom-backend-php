<?php
/**
 * Author: mark m /
 * Date:5/19/2020 8:29 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\tencent;
use Qcloud\Sms\SmsSingleSender;

/**
 * 腾讯云短信SDK封装
 * Class Sms
 * @package app\lib\tencent
 */

class Sms
{
    // 短信应用 SDK AppID
    protected $appid;
    // 短信应用 SDKAppKey
    protected $appkey;
    // 短信模板ID
    protected $templateId;
    // 短信签名内容
    protected $smsSign;

    public function __construct()
    {
        // 获取配置文件内容
        $config = config('tencent.sms');
        $this->appid = $config['appid'];
        $this->appkey = $config['appkey'];
        $this->templateId = $config['templateId'];
        $this->smsSign = $config['templateId'];
    }

    /**
     * 设置appid
     * @param string $appid
     * @return Sms
     */
    public function setAppid($appid)
    {
        $this->appid = $appid;
        return $this;
    }

    /**
     * 设置appkey
     * @param string $appkey
     * @return Sms
     */
    public function setAppkey($appkey)
    {
        $this->appkey = $appkey;
        return $this;
    }

    /**
     * 设置模板id
     * @param $templateId
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        return $this;
    }

    /**
     * 设置短信签名内容
     * @param $sign
     * @return $this
     */
    public function setSmsSign($sign)
    {
        $this->smsSign = $sign;
        return $this;
    }

    /**
     * 发送短信
     * @param $params array 短信变量参数值
     * @param $phoneNumber string 短信接收手机号码
     * @return array|string
     */
    public function send($params, $phoneNumber)
    {
        try {
            $sender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $sender->sendWithParam('86', $phoneNumber, $this->templateId, $params, $this->smsSign);
            // 腾讯云短信API返回的是json结果，这里把结果转成了数组方便存储或者后续处理
            return json_decode($result);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}

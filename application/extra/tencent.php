<?php
/**
 * Author: mark m /
 * Date:5/19/2020 8:31 PM
 * Email: markmei36@hotmail.com
 *
 */

/**
 * 腾讯云产品相关配置
 */
return [
    // 腾讯云短信
    'sms' => [ // 以下配置参数均需要通过腾讯云短信控制台查看获取
        // 短信应用 SDK AppID
        'appid' => '',
        // 短信应用 SDK AppKey
        'appkey' => '',
        // 短信模板 ID，需要在短信控制台中申请
        'templateId' => '',
        // 短信签名内容，需要是在控制台中已申请成功的
        'smsSign' => ''
    ]
];
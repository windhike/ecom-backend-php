<?php
/**
 * Author: mark m /
 * Date:4/23/2020 4:06 PM
 * Email: markmei36@hotmail.com
 *
 */

return[
    'app_id' => 'wxxxxxxxxxxxxxxxsss----xxxxx', #your app_id
    'app_secret' => '552bxxxxxxxxxxxxxsss----xxxxxxxxxx', #your app_secret
    'merchant_id' => '1900009851', #商户号（必须配置，开户邮件中可查看）//fake
    'sign_type' => 'MD5', #签名加密类型，直接MD5即可
    'key' => '8934e7d15453e97507ef794cf7b0519d', # 微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置//fake

    'code2session_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
//    GET https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
    'deliver_template_id' => 'prXVAgEwXpb6-8NUenW0yFgI5yx6ssS4gl75OIsGk4c', //=templateMsgId
    'msg_send_url' => "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=%s",

    // 证书路径，一定要是绝对路径，如果服务器是Windows，这个路径分隔符注意要写/不是\
    'cert_path' => '../cert/apiclient_cert.pem',//待补充
    'key_path' => '../cert/apiclient_key.pem',//待补充
];
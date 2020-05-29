<?php
/**
 * Author: mark m /
 * Date:4/23/2020 8:15 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class WxException extends BaseException
{
    public $code=400; //http status code
    public $msg='微信code2session api 调用失败'; //error detail info
    public $errorCode=999; //self-defined error code
}
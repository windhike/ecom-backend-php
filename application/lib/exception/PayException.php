<?php
/**
 * Author: mark m /
 * Date:5/19/2020 9:05 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class PayException extends BaseException
{
    public $code=400; //http status code
    public $msg='WxPay错误'; //error detail info
    public $errorCode=999; //self-defined error code
}
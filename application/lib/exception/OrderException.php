<?php
/**
 * Author: mark m /
 * Date:4/28/2020 9:41 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code=404; //http status code
    public $msg='订单不存在，请检查ID'; //error detail info
    public $errorCode=80000; //self-defined error code
}
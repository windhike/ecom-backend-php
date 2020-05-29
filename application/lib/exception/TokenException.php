<?php
/**
 * Author: mark m /
 * Date:4/25/2020 11:18 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code=401; //http status code
    public $msg='Token已经过期或无效'; //error detail info
    public $errorCode=10001; //self-defined error code
}
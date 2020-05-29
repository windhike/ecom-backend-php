<?php
/**
 * Author: mark m /
 * Date:4/26/2020 11:54 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class AddressException extends BaseException
{
    public $code=401; //http status code
    public $msg='Can not find uid'; //error detail info
    public $errorCode=10002; //self-defined error code
}
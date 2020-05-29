<?php
/**
 * Author: mark m /
 * Date:4/27/2020 12:47 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code=404; //http status code
    public $msg='User 不存在'; //error detail info
    public $errorCode=60000; //self-defined error code
}
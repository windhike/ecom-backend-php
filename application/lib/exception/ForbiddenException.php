<?php
/**
 * Author: mark m /
 * Date:4/27/2020 3:06 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code=403; //http status code
    public $msg='User\'s access is out of Scope '; //error detail info
    public $errorCode=10001; //self-defined error code
}
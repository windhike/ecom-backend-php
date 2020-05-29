<?php
/**
 * Author: mark m /
 * Date:4/19/2020 12:28 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code=400; //http status code
    public $msg='Params Error'; //error detail info
    public $errorCode=10002; //self-defined error code
}
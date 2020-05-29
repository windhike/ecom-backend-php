<?php
/**
 * Author: mark m /
 * Date:4/27/2020 1:12 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code=201; //http status code
    public $msg='ok'; //error detail info
    public $errorCode=0; //self-defined error code
}
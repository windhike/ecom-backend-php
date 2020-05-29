<?php
/**
 * Author: mark m /
 * Date:4/21/2020 8:46 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class ThemeMissException extends BaseException
{
    public $code=404; //http status code
    public $msg='Theme miss'; //error detail info
    public $errorCode=30000; //self-defined error code
}
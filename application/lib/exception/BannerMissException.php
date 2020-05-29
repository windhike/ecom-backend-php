<?php
/**
 * Author: mark m /
 * Date:4/18/2020 12:04 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code=404; //http status code
    public $msg='Banner miss '; //error detail info
    public $errorCode=40000; //self-defined error code
}
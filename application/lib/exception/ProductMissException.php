<?php
/**
 * Author: mark m /
 * Date:4/22/2020 11:00 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class ProductMissException extends BaseException
{
    public $code=404; //http status code
    public $msg='Product empty '; //error detail info
    public $errorCode=40001; //self-defined error code
}
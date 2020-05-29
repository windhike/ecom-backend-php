<?php
/**
 * Author: mark m /
 * Date:4/22/2020 6:34 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;



class CategoryException extends BaseException
{
    public $code=404; //http status code
    public $msg='Category miss '; //error detail info
    public $errorCode=50000; //self-defined error code
}
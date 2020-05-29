<?php
/**
 * Author: mark m /
 * Date:5/19/2020 4:28 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;



class LogisticsException extends BaseException
{
    public $code=400; //http status code
    public $msg='获取物流信息异常，请检查数据'; //error detail info
    public $errorCode=70000; //self-defined error code
}
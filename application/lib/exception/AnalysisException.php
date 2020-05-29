<?php
/**
 * Author: mark m /
 * Date:5/20/2020 12:07 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;



class AnalysisException extends BaseException
{
    public $code=400; //http status code
    public $msg='Analysis error'; //error detail info
    public $errorCode=60000; //self-defined error code
}
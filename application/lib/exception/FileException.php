<?php
/**
 * Author: mark m /
 * Date:5/23/2020 9:21 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


class FileException extends BaseException
{
    public $code = 413;
    public $msg  = '文件体积过大';
    public $errorCode = '60000';
}
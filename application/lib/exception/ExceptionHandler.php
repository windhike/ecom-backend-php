<?php
/**
 * Author: mark m /
 * Date:4/18/2020 10:09 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


use Exception;
use think\Config;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //--还要返回当前的url

    public function render(Exception $e)
    {

//        return parent::render($e); // TODO: Change the autogenerated stub

        if($e instanceof BaseException){ //if error is a instance of 自定义异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }
        else{
//            require('app_debug');
            if(Config::get('app_debug')){  // when app_debug turn on, return the parent original error info.
                return parent::render($e);
            }
            else{
                $this->code = 500;
                $this->msg = 'system internal error, do not present to frontend';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }

        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request-url'=> $request->url(),
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }
}
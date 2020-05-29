<?php
/**
 * Author: mark m /
 * Date:4/18/2020 10:16 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    public $code=400; //http status code
    public $msg='params error'; //error detail info
    public $errorCode=10000; //self-defined error code
/*  比较严谨的做法通常不直接将变量定义为public，而是如下文操作：
    private $name = ''; //用private/protected定义变量$name
    public function getName() // 用public的函数让外部可以访问$name，这样可以控制只读getName还是可以setName
    {
       return $name;
    }*/

    public function __construct($params=[])
    {
        if(!is_array($params)){
            return;
//           throw new Exception("params is not a array")
        }
        if(array_key_exists('code',$params)){
            $this->code=$params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg=$params['msg'];
        }
        if(array_key_exists('code',$params)){
            $this->errorCode=$params['errorCode'];
        }


    }

}
/*
999  未知错误
1 开头为通用错误
2 商品类错误
3 主题类错误
4 Banner类错误
5 类目类错误
6 用户类错误
8 订单类错误

10000 通用参数错误
10001 资源未找到
10002 未授权（令牌不合法）
10003 尝试非法操作（自己的令牌操作其他人数据）
10004 授权失败（第三方应用账号登陆失败）
10005 授权失败（服务器缓存异常）


20000 请求商品不存在

30000 请求主题不存在

40000 Banner不存在

50000 类目不存在

60000 用户不存在
60001 用户地址不存在

80000 订单不存在
80001 订单中的商品不存在，可能已被删除
80002 订单还未支付，却尝试发货
80003 订单已支付过*/
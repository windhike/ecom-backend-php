<?php
/**
 * Author: mark m /
 * Date:4/22/2020 11:27 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WxException;
use think\Exception;
use think\Request;

class UserToken extends BaseToken
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxCode2SessionUrl;

    function __construct($code)
    {
        $this->code=$code;
        $this->wxAppId=config('wx.app_id');
        $this->wxAppSecret=config('wx.app_secret');
        $this->wxCode2SessionUrl=sprintf(config('wx.code2session_url'),$this->wxAppId,$this->wxAppSecret,$this->code);
    }

/*      $cacheValue -- 即存在cache里的用户信息包括：$wxResult + $uid + $scope
            openid	string	用户唯一标识
            session_key	string	会话密钥
            unionid	string	用户在开放平台的唯一标识符，在满足 UnionID 下发条件的情况下会返回，详见 UnionID 机制说明。
            errcode	number	错误码
            errmsg	string	错误信息
            ---------- 前面是微信 code2session API 返回的结果，后面是本文处理后增加的结果-----------
            $cacheValue['uid']=$uid;
            $cacheValue['scope']=ScopeEnum::APP_USER;*/

    public function get(){  //get openId & session_key from 微信服务端
//        $result= Request::create($this->wxCode2SessionUrl,'GET');
        $result=curl_get($this->wxCode2SessionUrl);
        $wxResult=json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取微信session_key & openId异常，微信内部错误');
        }
        else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->throwWxLoginError($wxResult);
            }
            else{
                $token= $this->grantToken($wxResult);
                return $token;
            }

        }
    }

    private function throwWxLoginError($wxResult){
        throw new WxException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode'],
        ]);
    }

    private function grantToken($wxResult)
    {
        //拿到openId
        //去数据库中看openId是否存在，是：已有用户-->下一步，否：新用户-->添加一个数据库记录-->下一步；
        //生成token，准备缓存数据，写入缓存
        //把token返回给客户端
        //在缓存中存储token，openid是因为每次用户访问服务端的‘受保护/安全’API时，都会被要求携带Token（访问开放的API不需要）
        //而服务端都会校验token和openId，访问量大，数据库受不了，所以要用缓存。
        //在缓存中存的信息要包括：
        //Key：token
        //Value：uid，有时可以用uid信息替代token信息，因为时线性索引，简单），$wxResult 和 一个scope（用来指示该用户的权限，有些API该用户可以访问，有些不行）

        $openId=$wxResult['openid'];
        $user = UserModel::isUserExist($openId);

        if($user){
            $uid = $user->id;
        }
        else{
            $uid = $this->newUser($openId);
        }
        $cacheValue= $this->prepareCacheValue($wxResult,$uid);
        $token = $this->saveToCache($cacheValue);
        return $token;

    }

    private function newUser($openId){
        $user = UserModel::create(['openid' => $openId, ]);
        return $user->id;
    }

    private function prepareCacheValue($wxResult,$uid){
        $cacheValue = $wxResult;
        $cacheValue['uid']=$uid;
        $cacheValue['scope']=ScopeEnum::APP_USER; // 数字越大权限约大；16-> APP用户；32-> CMS（管理员）用户
        return $cacheValue;
    }

    private function saveToCache($cacheValue){
        $key=self::generateToken();
        $value=json_encode($cacheValue); // 将数组转成json字符串；如果用redis缓存系统，就可以直接存入数组/对象；但缺省用文件系统就必须是字符串
        $expire_in=config('myConfig.token_expire_in'); // 用于设置Token的有效时间

        $request = cache($key,$value,$expire_in); // tp5的辅助函数写入缓存，缺省是文件系统，也可以更改设置为redis等，代码不用改
        if (!$request){ //cache操作如果返回null，则异常
            throw new TokenException([
                'msg' => 'Server cache error',
                'errorCode'=>10005,
            ]);
        }
        return $key;
    }

}
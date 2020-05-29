<?php
/**
 * Author: mark m /
 * Date:4/25/2020 10:35 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\service;


use app\api\service\BaseToken as TokenService;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class BaseToken
{
    public static function generateToken(){
    //Token 就是一组随机字符串，由32个字符组成；
        $randChars = getRandChars(32); //getRandChars是通用功能，并且和Token无关，所以放到common.php中
        //用3组字符串再做一次MD5加密后返回，更安全；
        $timeStamp=$_SERVER['REQUEST_TIME_FLOAT'];
        //salt -- 一组自定义的随机字符
        $salt = config('secure.token_salt');

        return md5($randChars.$salt.$timeStamp);
    //这虽然随机的比较厉害，但好像并不能彻底排除不同的2个用户返回的一样的Token的情况？似乎严格的做法应该的返回前再检测一次缓存里是否已经存在同样的Tokenl。
    }

   public static function getCurrentTokenVar($key){ //获取当前Token对应的各种参数/vars，key是所需参数的域名；
        $token = Request::instance()->header('token'); // 约定token放在http的header中，而不是body
       if(!$token){
           throw new TokenException();
       }
       $vars=Cache::get($token);
       if(!$vars){
           throw new TokenException([
               'msg' => 'get vars by token miss',
               'errorCode' => 10003,
           ]);
       }
       else{
           if(!is_array($vars)){
               $vars = json_decode($vars,true); //转换成数组
           }
           if(array_key_exists($key,$vars)){
               return $vars[$key];
           }
           else{
               throw new Exception('查询token文件的key不存在');
           }

       }


   }
    public function getUidByToken(){
        return self::getCurrentTokenVar('uid');
    }

    public function getOpenIdByToken(){
        return self::getCurrentTokenVar('openid');
    }

    public function getScopeByToken(){
        return self::getCurrentTokenVar('scope');
    }

    public function checkUidByChallenge($challengeUid){
        if(!$challengeUid){
            throw new Exception('challengeUid 不能为空');
        }
        $uid = $this->getUidByToken();
        if($challengeUid ==$uid){
            return true;
        }
        return false;
    }

    public static function verifyToken($token){
        $exist = (new Cache())->get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }
}
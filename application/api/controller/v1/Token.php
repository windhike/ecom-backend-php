<?php
/**
 * Author: mark m /
 * Date:4/22/2020 11:13 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\BaseToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use think\Controller;
use think\Request;

class Token extends BaseController
{
    public function getToken($code=''){ //微信小程序发出的code
        (new TokenGet())->goCheck();

        $userToken = new UserToken($code);
        $token = $userToken->get();

        return ['token'=>$token];
    }

    public function verifyToken($token=''){
        $token = Request::instance()->header('token'); // 约定token放在http的header中，而不是body;token不校验
        if(!$token){
            throw  new  ParameterException(['token 不能为空']);
        }
        $valid = (new BaseToken())->verifyToken($token);
        return ['isValid'=>$valid];
    }

    /**
     * 第三方应用获取令牌 -- CMS系统用
     * @url /app_token?
     * @POST ac=:account se=:secret
     */
    public function getAppToken($ac='', $se='',$username='',$password='')
    {

        if(!$ac && $username){
            $ac = $username;
        }
        if(!$se && $password){
            $se = $password;
        }

        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return [
            'token' => $token
        ];
    }

}
<?php
/**
 * Author: mark m /
 * Date:4/26/2020 12:31 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\User;
use app\api\model\UserAddress as UserAddressModel;
use app\api\service\BaseToken as TokenService;
use app\api\validate\AddressValidate;
use app\lib\enum\ScopeEnum;
use app\lib\exception\AddressException;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;

class Address extends BaseController
{
    protected $beforeActionList=[
      'appAndSuperUserScope'=>['only'=>'createOrUpdateAddress'],
    ];


    public function createOrUpdateAddress(){
        $validate= (new AddressValidate());
        $validate->goCheck();


        //根据Token查cache获得对应uid
        //根据uid查user表确认该用户确实存在
        //获取用户从客户端发来的地址信息
        //根据地址信息是否存在，从而判断是增加地址还是update地址

        $uid = (new TokenService())->getUidByToken();
        $user = User::get($uid);
        if (!$user){
            throw new UserException();
        }
        else{
            $data=$validate->getDataByRule(input('post.'));
        }

        $userAddress = $user->addressOfUser($uid);
//        $userAddress = $user->address;

        if(!$userAddress){
            $data['user_id']=$uid;
            $userAddress = (new UserAddressModel());
            $userAddress->save($data);
//            $user->address()->save($data);
        }
        else{
            $userAddress->save($data,['user_id'=>$uid]);
//            $user->address->save($data);
        }
//        return $user->userWithAddress();
        return json(new SuccessMessage(),201);
    }

    public function getUserAddress(){  //必须带token
        $uid = (new TokenService())->getUidByToken();
        $userAddress = (new UserAddressModel())->where('user_id','=',$uid)->find();
        if (!$userAddress){
            throw new UserException([
                'msg'=>'用戶地址不存在',
                'errorCode'=>60001,
            ]);
        }
        return $userAddress;

    }
}
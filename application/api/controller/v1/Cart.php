<?php
/**
 * Author: mark m /
 * Date:5/12/2020 6:28 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\Cart as CartModel;
use app\api\model\User;
use app\api\service\BaseToken;
use app\api\service\BaseToken as TokenService;
use app\api\validate\CartValidate;
use app\api\validate\IsIdPositiveInt;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Cart extends BaseController
{
    public function getCartData(){
        $uid = (new BaseToken())->getUidByToken();

        $cartData = (new CartModel())->getCartDataByUid($uid);
        return $cartData;
    }

    public function updateCartData(){
        $validate= (new CartValidate());
        $validate->goCheck();

        //根据Token查cache获得对应uid
        //根据uid查user表确认该用户确实存在
        //获取用户从客户端发来的地址信息
        //根据地址信息是否存在，从而判断是增加地址还是update地址

        $uid = (new BaseToken())->getUidByToken();
        $user = User::get($uid);
        if (!$user){
            throw new UserException();
        }
        else{
            $data=$validate->getDataByRule(input('post.'));
        }
        $products=$data['products'];

        //先delete all DB record
        $cartDataByUser = (new CartModel())->where('user_id', '=', $uid)->select();
        if (!$cartDataByUser->isEmpty()) {
            (new CartModel())->where('user_id', '=', $uid)->delete(); //先删除所有的record
        }
        if (!$products) {  //if empty ,just delete all DB record,do nothing
        }
        else{ //if products is not empty, rewrite/update DB
            foreach ($products as $product){
                $saveCartData['product_id']=$product['id'];
                $saveCartData['counts']=$product['counts'];
                $saveCartData['selected_status']=$product['selectedStatus']?1:0;
                $saveCartData['user_id']=$uid;
                $cartData = new CartModel();
                $cartData->save($saveCartData); //recreate new record
            }
        }

        return json(new SuccessMessage(),201);

    }

}
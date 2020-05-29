<?php
/**
 * Author: mark m /
 * Date:4/26/2020 12:41 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class AddressValidate extends BaseValidate
{
    protected $message = [
        'name'=>'Name must be Not Empty',
        'mobile'=>'Mobile must be an mobile number',
        'province'=>'Province must be Not Empty',
        'city'=>'City must be Not Empty',
        'country'=>'Country must be Not Empty',
        'detail'=>'Detail must be Not Empty',

    ];

    protected $rule = [
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'country'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
         //'token'=>'require|isNotEmpty', // 客户端只有token没有uid，服务器通过token查cache后得到uid
    ];
}
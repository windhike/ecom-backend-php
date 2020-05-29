<?php
/**
 * Author: mark m /
 * Date:5/19/2020 4:36 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class OrderNoValidate extends BaseValidate
{
    protected $rule =[
        'orderNo'=>'require|length:16|alphaNum'
    ];
}
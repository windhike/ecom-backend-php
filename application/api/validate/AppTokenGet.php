<?php
/**
 * Author: mark m /
 * Date:5/10/2020 10:58 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'isNotEmpty',
        'se' => 'isNotEmpty',
        'username' => 'isNotEmpty',
        'password' => 'isNotEmpty',
    ];
}
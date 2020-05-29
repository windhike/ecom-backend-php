<?php
/**
 * Author: mark m /
 * Date:4/22/2020 11:15 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule=[
      'code' => 'require|isNotEmpty',
    ];

    protected $message=[
        'code' => 'Null user code',
    ];
}
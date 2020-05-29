<?php
/**
 * Author: mark m /
 * Date:5/18/2020 9:42 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class isPositiveArray extends BaseValidate
{
    protected $rule=[
      'ids'=>'require|array|min:1',
    ];
}
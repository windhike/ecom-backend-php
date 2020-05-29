<?php
/**
 * Author: mark m /
 * Date:5/18/2020 4:12 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class ProductPaginateParameter extends BaseValidate
{
    protected $rule = [
        'page'=>'require|number',
        'count'=>'isPositiveInteger|between:1,15',
    ];
    protected $message = [
        'page'=>'查询页码必须是正整数',
        'count'=>'单页查询数量必须是正整数1~15',
    ];
}
<?php
/**
 * Author: mark m /
 * Date:5/19/2020 4:50 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class DeliverRecordForm extends BaseValidate
{
    protected $rule = [
        'page' => 'require|number',
        'count' => 'require|number|between:1,15',
        'order_no' => 'length:16|alphaNum',
        'number' => 'alphaNum',
        'operator' => 'chsAlphaNum'
    ];
}
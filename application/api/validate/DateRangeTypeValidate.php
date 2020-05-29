<?php
/**
 * Author: mark m /
 * Date:5/20/2020 12:14 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class DateRangeTypeValidate extends BaseValidate
{
    protected $rule=[
        'start'=>'require|date',
        'end'=>'require|date',
        'type'=>'require',
    ];
}
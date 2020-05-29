<?php
/**
 * Author: mark m /
 * Date:5/2/2020 3:00 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
      'page'=>'require|number',
      'size'=>'isPositiveInteger',
    ];
    protected $message = [
      'page'=>'分页参数必须>=0',
      'size'=>'分页参数必须是正整数',
    ];
}
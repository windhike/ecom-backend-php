<?php
/**
 * Author: mark m /
 * Date:4/21/2020 10:09 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class IdCollection extends BaseValidate
{
    protected $rule = [
            'ids' => 'require|idsCheck'
        ];

    protected $message = [
      'ids' => 'ids 必须时多个逗号分隔的正整数'
    ];

    protected function idsCheck($value){
        $value =  explode(',',$value);
        if(empty($value)){
            return false;
        }
        foreach ($value as $id) {
            if(! $this->isPositiveInteger($id)){
                return false;
            }
        }
        return true;
    }
}
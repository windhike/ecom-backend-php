<?php
/**
 * Author: mark m /
 * Date:4/22/2020 3:01 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $message = [
        'count' => "Recent product count must <= 'myConfig.recent_product_max_count' and be a positive integer"
    ];

    protected $rule = [
        'count' => 'isPositiveInteger|inRange'
    ];


    protected function inRange($count){
        if ($count > config('myConfig.recent_product_max_count')){
            return false;
        }
        else{
            return true;
        }

    }

}
<?php
/**
 * Author: mark m /
 * Date:4/27/2020 10:53 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;

class OrderPlaceValidate extends BaseValidate
{
    protected $rule = [
        'products'=>'require|checkProducts',
    ];

    protected $singleRule = [ // 针对单个产品的参数校验rule；
        'product_id'=>'require|isPositiveInteger',
        'count'=>'require|isPositiveInteger',

    ];

    protected function checkProducts($value){
        if(empty($value)){
            throw new ParameterException(['msg'=> 'Products order must be not empty and must be an array']);
        }

        foreach ($value as $product){
            $this->checkProduct($product);
        }
        return true;
    }

    protected function checkProduct($value){
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);

        if(!$result){
            throw new ParameterException(['msg'=> 'Product parameter must be not empty and must be positive int']);
        }
//        return true;
    }
}
<?php
/**
 * Author: mark m /
 * Date:5/13/2020 12:59 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class CartValidate extends BaseValidate
{

// 要参考orderPlace的数组检查方法：
    protected $rule =[
        'products'=>'checkProducts', //products 可以为空，表示cart为空，删除所有记录
    ];

    protected $singleRule = [ // 针对单个产品的参数校验rule；
        'id'=>'require|isPositiveInteger', //product_id
        'counts'=>'require|isPositiveInteger',
        'selectedStatus'=>'require',
    ];



    protected function checkProducts($value){
        if(empty($value)){
//            throw new ParameterException(['msg'=> 'Products order must be not empty and must be an array']);
            return true; //允许空，删除所有数据；
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
        return true;
    }
}
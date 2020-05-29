<?php
/**
 * Author: mark m /
 * Date:4/16/2020 10:12 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


//use think\Validate;

class IsIdPositiveInt extends BaseValidate  //inherit to BaseValidate --> Validate;
{
    protected $rule = [
        //why use "protected",这个变量不是被父类Valitdate checkItem（）调用了吗？
        // anws：不是这样的。其实实例化的是一个IsIdPositiveInt类-- $result = new IsIdPositiveInt();
        // 然后这个实例去调用父类Valitdate checkItem（）-- $result->goCheck();
        // 并不是父类Valitdate checkItem（）调用这个实例的protect参数。

        'id'=>'require|isPositiveInteger',
    ];

    protected $message = [
        'id' => 'ID 必须是正整数'
    ];

   /* protected function isPositiveInteger($value, $rule = '',$data='',$field=''){
        if (is_numeric($value) && $value>0 && $value==floor($value)){

            return(true);
        }
        else {
            return "$field is not an integer which > 0";
        }
    }*/
}
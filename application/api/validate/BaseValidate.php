<?php
/**
 * Author: mark m /
 * Date:4/17/2020 12:04 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        /*why use 'public'?
        public:    可以class（所有class）内部调用，可以实例化调用。
        private:   可以class（只有自身）内部调用，实例化调用报错。
        protected：  可以class(包括子class，自身)内部调用，实例化调用报错。
        由于pri..和pro..不能实例化调用，即使自身class的实例也不能调用，使用非常麻烦。所以只有明确就是本class范围内专用的属性才设置为pro/pri
        default：是public；

        */

        // get 'all' http param
        $request = Request::instance();
        $params = $request->param();

        // check param
        $result = $this->batch()->check($params,$this->rule);
        if(!$result){
            $error = new ParameterException(['msg'=>$this->error]);
            throw $error;
        }
        else{
            return true;
        }
    }

    protected function isPositiveInteger($value, $rule = '',$data='',$field=''){
        if (is_numeric($value) && $value>0 && $value==floor($value)){

            return true ;
        }
        else {
            return false;
        }
    }

    protected function isNotEmpty($value,$rule = '',$data='',$field=''){
        if (empty($value)){
            return false;
        }
        else{
            return true;
        }
    }

    //没有使用TP的正则验证，集中在一处方便以后修改
    //不推荐使用正则，因为复用性太差
    //手机号的验证规则
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
    public function getDataByRule($allParams)
    {
        if (array_key_exists('user_id', $allParams) | array_key_exists('uid', $allParams)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $data = [];
        foreach ($this->rule as $key => $value) {
            $data[$key] = $allParams[$key];
        }
        return $data;
    }


}
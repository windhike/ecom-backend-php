<?php
/**
 * Author: mark m /
 * Date:5/18/2020 9:25 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class ProductPropertyForm extends BaseValidate
{
    protected $rule = [
        'property' => 'require|array|min:1|productProperty',
    ];

    public function sceneEdit()
    {
//        return $this->remove('property', 'productProperty')
//            ->append('property', 'requireId');
        $this->rule = ['property' => 'require|array|min:1|requireId'];
        return $this;
    }

    protected function productProperty($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['product_id']) || empty($v['product_id'])) {
                    return '商品属性所属商品id不能为空';
                }

                if (!isset($v['name']) || empty($v['name'])) {
                    return '商品属性名称不能为空';
                }
                if (!isset($v['detail']) || empty($v['detail'])) {
                    return '商品属性' . $v['name'] . '的详情不能为空';
                }
            }
        }

        return true;
    }

    protected function requireId($value)
    {
        foreach ($value as $v) {
            if (!isset($v['id']) || empty($v['id'])) {
                return '商品属性主键id不能为空';
            }

            if (!isset($v['name']) || empty($v['name'])) {
                return '商品属性名称不能为空';
            }
            if (!isset($v['detail']) || empty($v['detail'])) {
                return '商品属性' . $v['name'] . '的详情不能为空';
            }
        }
        return true;
    }
}
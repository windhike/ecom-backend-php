<?php
/**
 * Author: mark m /
 * Date:5/18/2020 8:03 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class ProductForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'category_id' => 'number',
        'img_id' => 'require|number',
        'main_img_url' => 'require|url',
        'price' => 'require|float',
        'stock' => 'require|number',
        'summary' => 'chsDash',
        'image' => 'array|productImage',
        'property' => 'array|productProperty',
    ];

    // 场景声明
    public function sceneEdit()
    {
//        return $this->append('id', ['require', 'number']);
        $this->rule('id','require|number');
        return $this; //这样可以链式调用
    }

    protected function productImage($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['img_id']) || empty($v['img_id'])) {
                    return '商品详情图不能为空';
                }
            }
        }
        return true;
    }

    protected function productProperty($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
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


}
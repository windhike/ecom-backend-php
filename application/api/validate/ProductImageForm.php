<?php
/**
 * Author: mark m /
 * Date:5/18/2020 9:19 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class ProductImageForm extends BaseValidate
{
    protected $rule = [
        'image' => 'require|array|min:1|productImage',
    ];

    public function sceneEdit()
    {
//        return $this->remove('image', 'productImage')
//            ->append('image', 'requireId');
        $this->rule = ['image' => 'require|array|min:1|requireId',];
        return $this;
    }

    protected function productImage($value)
    {
        foreach ($value as $v) {
            if (!isset($v['product_id']) || empty($v['product_id'])) {
                return '商品详情图所属商品id不能为空';
            }

            if (!isset($v['img_id']) || empty($v['img_id'])) {
                return '商品详情图不能为空';
            }
        }
        return true;
    }

    protected function requireId($value)
    {
        foreach ($value as $v) {
            if (!isset($v['id']) || empty($v['id'])) {
                return '商品详情图主键id不能为空';
            }

            if (!isset($v['product_id']) || empty($v['product_id'])) {
                return '商品详情图所属商品id不能为空';
            }

            if (!isset($v['img_id']) || empty($v['img_id'])) {
                return '商品详情图不能为空';
            }
        }
        return true;
    }
}
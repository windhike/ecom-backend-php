<?php
/**
 * Author: mark m /
 * Date:5/18/2020 11:27 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class BannerItemForm extends BaseValidate
{
    protected $rule = [
        'items' => 'array|require|min:1',
    ];

    public function sceneEdit()
        {
//            return $this->append('items', 'checkEditItem');
            $this->rule('items',$this->rule['items'].'|checkEditItem');
            return $this;

        }

        public function sceneAdd()
    {
//        return $this->append('items', 'checkAddItem');
            return $this->rule('items',$this->rule['items'].'|checkAddItem');
    }

    protected function checkAddItem($value)
    {
        foreach ($value as $k => $v) {
            if (!empty($v['id'])) {
                return '新增轮播图元素不能包含id';
            }
            if (empty($v['img_id']) || empty($v['key_word']) || empty($v['type']) || empty($v['banner_id'])) {
                return '轮播图元素信息不完整';
            }
        }
        return true;
    }

    protected function checkEditItem($value)
    {
        foreach ($value as $k => $v) {
            if (empty($v['id'])) {
                return '轮播图元素id不能为空';
            }
            if (empty($v['img_id']) || empty($v['key_word']) || empty($v['type'])) {
                return '轮播图元素信息不完整';
            }
        }
        return true;
    }

}
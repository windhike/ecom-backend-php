<?php
/**
 * Author: mark m /
 * Date:5/18/2020 12:38 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class ThemeForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require|chsDash',
        'description' => 'require|chsDash',
        'topic_img_id' => 'require|number',
        'head_img_id' => 'require|number'
    ];

    public function sceneEdit()
    {
//        return $this->remove('name', 'require')
//            ->remove('description', 'require')
//            ->remove('topic_img_id', 'require')
//            ->remove('head_img_id', 'require');

        $this->rule('name',str_replace("require|", "", $this->rule['name']));
        $this->rule('description',str_replace("require|", "", $this->rule['description']));
        $this->rule('topic_img_id',str_replace("require|", "", $this->rule['topic_img_id']));
        $this->rule('head_img_id',str_replace("require|", "", $this->rule['head_img_id']));
        return $this;
    }
}
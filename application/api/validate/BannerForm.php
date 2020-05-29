<?php
/**
 * Author: mark m /
 * Date:5/17/2020 11:38 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\validate;


class BannerForm extends BaseValidate
{
        protected $message = [
            'name'=>'name must not be empty',
            'items.require'=>'items must not be empty',
            'items.array'=>'items must be array',

        ];

        protected $rule = [

            'name'=>'require',
            'items'=>'require|array|min:1',
        ];
}
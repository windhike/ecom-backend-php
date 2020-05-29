<?php
/**
 * Author: mark m /
 * Date:4/20/2020 11:57 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


use think\Model;

class Image extends BaseModel
{
    protected $hidden = ['update_time','delete_time',];
    public function getUrlAttr($url,$data){
        //这个函数是当程序访问Image对象的url属性时（$img->url），会自动触发。
        //当前代码虽然没有在显式访问url属性，但是在控制器return $banner时，框架其实访问了$Img->url属性。
    return $this->addImgPrefixToUrl($url,$data);
}

}
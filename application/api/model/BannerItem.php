<?php
/**
 * Author: mark m /
 * Date:4/20/2020 5:41 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class BannerItem extends BaseModel
{
    use SoftDelete;//使能软删除
//    protected $table='banner_item'; // 缺省模式：Model::get()的table名就是class的名字‘BannerItem’，如果要指定table，则给变量$table赋具体的table名
    protected $hidden = ['update_time','delete_time','banner_id'];

    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}
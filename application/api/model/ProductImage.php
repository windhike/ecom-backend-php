<?php
/**
 * Author: mark m /
 * Date:4/26/2020 10:36 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['delete_time','img_id','product_id'];


    public function imgUrl(){
//        return $this->belongsTo('Image','img_id','id');
       return (new ProductImage($this->order('order','asc')))->belongsTo('Image','img_id','id');
       //先对productImage排序，然后再关联Image；
    }

    public function img()
    {
        return $this->belongsTo('Image', 'img_id');
    }
}
<?php
/**
 * Author: mark m /
 * Date:4/22/2020 6:27 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden=['delete_time','update_time','create_time'];

    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public static function getCategoryList(){
      return $categoryList=(new Category())->with('topicImg')->select();
    }

    public function product(){
        return $this->hasMany('Product','category_id','id');
    }

    public static function getCategoryWithProducts($id){
        return $products = (new Category)->with(['product','topicImg'])->select($id);
    }
}
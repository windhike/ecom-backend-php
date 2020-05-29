<?php
/**
 * Author: mark m /
 * Date:5/12/2020 6:26 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


class Cart extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','id','uid'];

    public function product(){
        return $this->belongsTo('Product','product_id','id');
    }

    public static function getCartDataByUid($uid){
        $newCartData=[];
        $cartProductData = (new Cart())->with('product')->where('user_id','=',$uid)->select();
       foreach ($cartProductData as $item) {
            $cartData = [
                'id'=>$item->product->id,
                'name'=>$item->product->getAttr('name'),
                'main_img_url'=>$item->product->getAttr('main_img_url'),
                'price'=>$item->product->getAttr('price'),
               'counts'=>$item->counts,
                'selectedStatus'=>$item->selected_status,
            ];
            array_push($newCartData,$cartData);

       }
       return json($newCartData);
    }
}
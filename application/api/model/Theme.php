<?php
/**
 * Author: mark m /
 * Date:4/21/2020 6:41 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


use think\Db;
use think\Exception;
use traits\model\SoftDelete;

class Theme extends BaseModel
{
    use SoftDelete;
    protected $hidden=['topic_img_id','head_img_id','update_time','delete_time'];


    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }

    public static function  getThemeList(){
        //todo:normal, should get theme by id; if theme miss, return null and do exception or log;

        return $theme = (new Theme())->with(['topicImg','headImg'])->select();
    }

    public function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function  getThemeWithProducts($id){
        $themeProducts = (new Theme())->with(['products','topicImg','headImg'])->select($id);
        return $themeProducts;
    }

    /**
     * @param $ids
     * @return bool
     */
    public static function delTheme($ids)
    {
        // 开启事务
        Db::startTrans();
        try {
            // 对theme表记录做软删除
            self::destroy($ids);
            // 删除中间表中对应主题id的记录,注意这里是执行硬删除
            foreach ($ids as $id) {
                // 条件查询，theme_id字段等于$id的记录
                (new ThemeProduct)->where('theme_id', $id)->delete();
            }
            Db::commit();
            return true;
        } catch (Exception $ex) {
            Db::rollback();
            return false;
        }
    }

}
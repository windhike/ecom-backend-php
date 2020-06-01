<?php
/**
 * Author: mark m /
 * Date:4/17/2020 11:09 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


use app\lib\exception\BannerMissException;
use think\Db;
use think\Exception;
use think\Log;
use think\Model;
use traits\model\SoftDelete;


class Banner extends BaseModel
{
    // 开启自动写入时间戳
    public $autoWriteTimestamp = true;

    use SoftDelete;//使能软删除
    //protected $table='banner_item'; // 缺省模式：Model::get()的table名就是class的名字‘banner’，如果要指定table，则给变量$table赋具体的table名
    protected $hidden = ['update_time','delete_time'];
    //        protected $visible(['id','update_time']);
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerByID($id){
        //why use 'static'? 静态函数最大的好处就是类不经过实例化就可以直接实用.但它不能访问类的非静态成员变量和成员函数.

        //todo:normal, should get banner by id; if banner miss, return null and do exception or log;

        return $banner = (new Banner)->with(['items','items.img'])->find($id);

    }

    public static function getAllBanner(){

        return $banner = (new Banner)->with(['items','items.img'])->select();

    }

    public static function add($parmas)
    {
        Db::startTrans();
        try{
            // 调用当前模型的静态方法create()，第一个参数为要写入的数据，第二个参数标识仅写入数据表定义的字段数据
            $banner = self::create($parmas, true);
            // 调用关联模型，实现关联写入；saveAll()方法用于批量新增数据
            $banner->items()->saveAll($parmas['items']);
            Db::commit();
            return $banner->id;
        }
        catch (Exception $ex)
        {
            Db::rollback();
            throw new BannerMissException([
                'msg'=>'add banner fail',
                'errorCode'=>70001,
            ]);
        }

    }
}
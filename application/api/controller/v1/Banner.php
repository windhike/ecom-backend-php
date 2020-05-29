<?php
/**
 * Author: mark m /
 * Date:4/15/2020 11:43 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;

use app\api\model\BannerItem as BannerItemModel;
use app\api\validate\BannerForm;
use app\api\validate\BannerItemForm;
use app\api\validate\IsIdPositiveInt;
use app\api\model\Banner as BannerModel;
use app\api\validate\isPositiveArray;
use app\lib\exception\BannerMissException;
use app\lib\exception\ParameterException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Hook;
use think\Request as Request;
use think\response\Json;


class Banner extends BaseController
{
/*获取指定id的Banner信息
 * url /Banner/:id
 * http GET
 * id Banner表 id
 * */

    public function getBanner($id){

        (new IsIdPositiveInt())->goCheck();
        // if goCheck the $id is illegal, goCheck() will thrown an Exception() then exit;
        //if $id is legal, will process following code:

        $banner = BannerModel::getBannerByID($id);

        if (!$banner){  // if banner == null, throw banner miss exception;
            throw new BannerMissException();
        }
        else{
            return $banner;
        }

    }

    public function getAllBanner(){
        return BannerModel::getAllBanner();
    }

    public function addBanner(){
        $params = Request::instance()->post();
        (new BannerForm())->goCheck();

        $bannerId = (new BannerModel())->add($params);

        return json(new SuccessMessage(['msg'=>'newBannerId='.$bannerId]),201);
    }

    /**
     * @param('ids','待删除的轮播图id列表','require|array|min:1')
     * @return Json
     */
    public function deleteBanner()
    {

//        $ids = Request::instance()->delete('ids');
        $ids = Request::instance()->delete()['ids'];
        (new isPositiveArray())->goCheck();

        array_map(function ($id) {
            // 查询指定id的轮播图记录
            $banner = BannerModel::get($id,'items');
            // 指定id的轮播图不存在则抛异常
            if (!$banner) throw new BannerMissException(['msg' => 'id为'.$id.'的轮播图不存在']);
            // 执行关联删除
            Db::startTrans();
            try {
                $banner->together('items')->delete();
                BannerItemModel::destroy(['banner_id'=>$id]);

                Db::commit();
            }
            catch (Exception $ex){
                Db::rollback();
                throw new BannerMissException(['msg'=>'delete banner error']);
            }


        }, $ids);
//        return writeJson(201, [], '轮播图删除成功！');
        $params = '删除了id为' . implode(',', $ids) . '的banner';
        Hook::listen('logger', $params);

        return Json(new SuccessMessage(['msg'=>'轮播图删除成功']),201);
    }

    /**
     * 编辑轮播图基础信息
     * @param $id
     * @param('id','轮播图id','require|number')
     * @param('name','轮播图名称','require')
     */
    public function editBannerInfo($id)
    {
        (new IsIdPositiveInt())->goCheck();
        $bannerInfo = Request::instance()->patch();
        $banner = BannerModel::get($id);
        if (!$banner) throw new BannerMissException(['msg' => 'id为' . $id . '的轮播图不存在']);
        $banner->save($bannerInfo);
//        return writeJson(201, [], '轮播图基础信息更新成功！');
        return Json(new SuccessMessage(['msg'=>'轮播图基础信息更新成功']),201);
    }

    /**
     * 新增轮播图元素
     * @validate('BannerItemForm.add')
     * @return Json
     * @throws ParameterException
     */
    public function addBannerItem()
    {
        $data = Request::instance()->post()['items'];
        $validate=(new BannerItemForm());
        $validate->sceneAdd();
        $validate->goCheck();
        foreach ($data as $key => $value) {
            BannerItemModel::create($value);
        }
//        return writeJson(201, [], '新增轮播图元素成功！');
        return Json(new SuccessMessage(['msg'=>'新增轮播图元素成功']),201);
    }

    /**
     * 编辑轮播图元素
     * @validate('BannerItemForm.edit')
     * @throws \Exception
     */
    public function editBannerItem()
    {
        $data = Request::instance()->put()['items'];
        (new BannerItemForm())->sceneEdit()->goCheck();

        $bannerItem = new BannerItemModel;
        # allowField(true)表示只允许写入数据表中存在的字段。
        # saveAll()接收一个数组，用于批量更新或者新增。通过判断传入的数组中是否设置了id属性，如果有则视为更新，无则视为新增
        $bannerItem->allowField(true)->saveAll($data);
//        return writeJson(201, [], '编辑轮播图元素成功！');
        return Json(new SuccessMessage(['msg'=>'编辑轮播图元素成功']),201);
    }

    /**
     * 删除轮播图元素
     * @param('ids','待删除的轮播图元素id列表','require|array|min:1')
     * @return
     */
    public function delBannerItem()
    {
        $ids = Request::instance()->delete()['ids'];
        (new isPositiveArray())->goCheck();
        // 传入多个id组成的数组进行批量删除
        BannerItemModel::destroy($ids);

        $params = '删除了id为' . implode(',', $ids) . '的bannerItem';
        Hook::listen('logger', $params);

//        return writeJson(201, [], '轮播图元素删除成功！');
        return Json(new SuccessMessage(['msg'=>'轮播图元素删除成功']),201);
    }
}
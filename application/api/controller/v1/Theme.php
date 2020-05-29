<?php

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IdCollection;
use app\api\validate\IsIdPositiveInt;
use app\api\validate\isPositiveArray;
use app\api\validate\ThemeForm;
use app\lib\exception\ProductMissException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\ThemeMissException;
use think\Controller;
use think\Hook;
use think\Request;

class Theme extends BaseController
{
    /*
     * @url /theme/ids=id1,id2,id3,...
     * @return 一组Theme模型
    */
    public function getSimpleList(){

//        (new IdCollection())->goCheck();
//        $ids = explode(',',$ids);

        $theme = ThemeModel::getThemeList();

        if ($theme->isEmpty()){  // if theme == null, throw banner miss exception;
            throw new ThemeMissException();
        }
        else{
            return $theme;
        }
    }

    /*
     * @url /theme/:id
     * @return 一组Theme模型
    */
    public function getComplexProductList($id){
        (new IsIdPositiveInt())->goCheck();

        $result = ThemeModel::getThemeWithProducts($id);

        foreach($result[0]->products as $k=>$product) {
            if ($product['status']==0){         //unavailable product 过滤掉
                unset($result[0]->products[$k]);
            }
        }
        if ($result->isEmpty()){  // if theme == null, throw banner miss exception;
            throw new ProductMissException();
        }
        else{
            return $result;
        }
    }

    /**
     * 新增精选主题
     * @validate('ThemeForm')
     * @throws ThemeMissException
     */
    public function addTheme()
    {
        // 获取post请求参数内容
        $params = Request::instance()->post();
        (new ThemeForm())->goCheck();
        // 调用模型的create()方法创建theme表记录，内容是获取到的参数内容，并仅允许写入数据表定义的字段数据
        $theme = ThemeModel::create($params, true);
        if (!$theme) {
            throw new ThemeMissException([
                'msg' => '精选主题新增失败'
            ]);
        }
//        return writeJson(201, ['id' => $theme->id], '精选主题新增成功！');
        return Json(['result'=>$theme,new SuccessMessage(['msg'=>'精选主题新增成功'])], 201);
    }

    /**
     * @auth('删除精选主题','精选主题管理')
     * @param('ids','待删除的主题id数组','require|array|min:1')
     */
    public function delTheme()
    {
        $ids = Request::instance()->delete()['ids'];
        (new isPositiveArray())->goCheck();
        // 调用模型内封装好的方法
        $res = ThemeModel::delTheme($ids);
        if (!$res) throw new ThemeMissException(['msg' => '精选主题删除失败']);
        // 记录本次行为的日志
        $params = '删除了id为' . implode(',', $ids) . '的精选主题';
        Hook::listen('logger', $params);
//        return writeJson(201, [], '精选主题删除成功！');
        return Json(new SuccessMessage(['msg'=>'精选主题删除成功']),201);
    }

    /**
     * 更新精选主题信息
     * @validate('ThemeForm.edit')
     */
    public function updateThemeInfo($id)
    {
        (new IsIdPositiveInt())->goCheck();
        $themeInfo = Request::instance()->patch();
        (new ThemeForm())->sceneEdit()->goCheck();

        $theme = ThemeModel::get($id);
        if (!$theme) throw new ThemeMissException(['msg' => '指定的主题不存在']);
        $theme->save($themeInfo);

//        return writeJson(201, [], '精选主题基础信息更新成功！');
        return Json(new SuccessMessage(['msg'=>'精选主题基础信息更新成功']),201);
    }

    /**
     * 移除精选主题关联商品
     * @param('id','精选主题id','require|number')
     * @param('products','商品id列表','require|array|min:1')
     */
    public function removeThemeProduct($id)
    {
        (new IsIdPositiveInt())->goCheck();
        $products = Request::instance()->post()['products'];
        $theme = ThemeModel::get($id);
        if (!$theme) throw new ThemeMissException(['msg' => '指定的主题不存在']);
        $theme->products()->detach($products);

//        return writeJson(201, [], '精选专题删除商品成功');
        return Json(new SuccessMessage(['msg'=>'精选专题删除商品成功']),201);
    }

    /**
     * 新增精选主题关联商品
     * @param('id','精选主题id','require|number')
     * @param('products','商品id列表','require|array|min:1')
     */
    public function addThemeProduct($id)
    {
        (new IsIdPositiveInt())->goCheck();
        $products = Request::instance()->post()['products'];
        $theme = ThemeModel::get($id);
        if (!$theme) throw new ThemeMissException(['msg' => '指定的主题不存在']);
        $theme->products()->saveAll($products);

//        return writeJson(201, [], '精选专题新增商品成功');
        return Json(new SuccessMessage(['msg'=>'精选专题新增商品成功']),201);
    }

}

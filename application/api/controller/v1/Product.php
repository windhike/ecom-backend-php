<?php
/**
 * Author: mark m /
 * Date:4/22/2020 12:27 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\ProductImage;
use app\api\model\ProductProperty;
use app\api\validate\Count;
use app\api\validate\IsIdPositiveInt;
use app\api\validate\isPositiveArray;
use app\api\validate\ProductForm;
use app\api\validate\ProductImageForm;
use app\api\validate\ProductPaginateParameter;
use app\api\validate\ProductPropertyForm;
use app\lib\exception\ProductMissException;
use app\lib\exception\SuccessMessage;
use think\Controller;
use app\api\model\Product as ProductModel;
use think\Db;
use think\Exception;
use think\Hook;
use think\Request;

class Product extends BaseController
{
    //管理员才有删除权限；
/*    protected $beforeActionList=[
        'superUserOnlyScope'=>['only'=>
            'delProductProperty,updateProductProperty,addProductProperty,
            delProductImage,updateProductImage,addProductImage,updateProduct,delProduct,addProduct,modifyStatus'
        ],
    ];*/

    public function getRecentProducts($count=15){

        (new Count())->goCheck();

        $productList = ProductModel::getRecentProductList($count);
        if ($productList->isEmpty()){
            throw new ProductMissException();
        }
        else{
//            $transferToClass = collection($productList);
            //collection function can transfer array to a set of class(collection type); then can use whole function of the class;

            $productList = $productList->hidden(['summary']);
            return $productList;
        }

    }

    public function getAllByCategory($id){
        (new IsIdPositiveInt())->goCheck();

        $productList = ProductModel::getAllByCategoryId($id);

        if ($productList->isEmpty()){
            throw new ProductMissException();
        }
        else{

            return $productList;
        }

    }

    public function getOne($id){
        (new IsIdPositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);

        if($product->isEmpty()){
            throw new ProductMissException();
        }
        else{
            return $product;
        }
    }

    /**
     * 查询所有商品，分页
     * @param('page','查询页码','require|number')
     * @param('count','单页查询数量','require|number|between:1,15')
     */
    public function getProductsPaginate()
    {
        $params = Request::instance()->get();
        (new ProductPaginateParameter())->goCheck();
        $products = ProductModel::getProductsPaginate($params);
        if ($products['total_nums'] === 0) {
            throw new ProductMissException([
                'code' => 404,
                'msg' => '未查询到相关商品',
                'errorCode' => '70006'
            ]);
        }
        return $products;
    }

    /**
     * 查询所有可用商品，用于给前端某些功能的选项列表使用
     */
    public function getProducts()
    {
        $products = (new ProductModel)->where('status', 1)->select();
        if ($products->isEmpty()) {
            throw new ProductMissException([
                'code' => 404,
                'msg' => '未查询到相关商品'
            ]);
        }
        return $products;
    }

    /**
     * 商品上架/下架
     * @auth('商品上架/下架','商品管理')
     * @param('id','商品id','require|number')
     */
    public function modifyStatus($id)
    {
        (new IsIdPositiveInt())->goCheck();
        $product = ProductModel::get($id);
        if (!$product) {
            throw new ProductMissException([
                'code' => 404,
                'msg' => '未查询到相关商品',
                'errorCode' => '70006'
            ]);
        }
        $product->status = !$product->status;
        $product->save();
//        return writeJson(201, [], '状态已经修改');
        return Json(new SuccessMessage(['msg'=>'状态已经修改']),201);
    }

    /**
     * 新增商品
     * @validate('ProductForm')
     */
    public function addProduct()
    {
        $params = Request::instance()->post();
        (new ProductForm())->goCheck();
        // $params['main_img_url'] 是一个完整的url。
        // $array = explode(config('setting.img_prefix'), $params['main_img_url']);
        // $params['main_img_url'] = $array[1]
        $params['main_img_url'] = explode(config('myConfig.image_prefix'), $params['main_img_url'])[1];
        $product = ProductModel::create($params, true);
        if (!$product) {
            throw new ProductMissException([
                'msg' => '商品创建失败'
            ]);
        }
        $product->image()->saveAll($params['image']);
        $product->property()->saveAll($params['property']);

//        return writeJson(201, [], '商品新增成功');
        return Json(new SuccessMessage(['msg'=>'商品新增成功']),201);
    }

    /**
     * 删除商品
     * @auth('删除商品','商品管理')
     * @param('ids','待删除的商品id列表','require|array|min:1')
     */
    public function delProduct()
    {
        $ids = Request::instance()->delete()['ids'];
        array_map(function ($id) {
            // get()方法第二个参数传入关联模型的方法名实现关联查询
            $product = ProductModel::get($id, 'image,property');
            // 如果product存在，做关联删除
            if (!$product) throw new ProductMissException(['msg' => 'id为'.$id.'的商品不存在']);
                // 在delete()之前调用together()并传入关联模型方法名实现关联删除
                Db::startTrans();
                try {
                    $product->together('image,property')->delete();
                    ProductImage::destroy(['product_id' => $id]);
                    ProductProperty::destroy(['product_id' => $id]);
                    Db::commit();
                }
                catch (Exception $ex){
                    Db::rollback();
                    throw new ProductMissException(['msg'=>'delete product error']);
                }
        }, $ids);


        $params = '删除了id为' . implode(',', $ids) . '的商品';
        Hook::listen('logger', $params);

//        return writeJson(201, [], '商品删除成功');
        return Json(new SuccessMessage(['msg'=>'商品删除成功']),201);
    }

    /**
     * 更新商品基础信息
     * @validate('ProductForm.edit')
     */
    public function updateProduct()
    {
/*        $validate=new ProductForm();
        $validate->sceneEdit();
        $validate->goCheck();*/

        (new ProductForm())->sceneEdit()->goCheck();

        $params = Request::instance()->put();
        $params['main_img_url'] = explode(config('myConfig.image_prefix'), $params['main_img_url'])[1];
        ProductModel::update($params);
//        return writeJson(201, '商品信息更新成功');
        return Json(new SuccessMessage(['msg'=>'商品信息更新成功']),201);
    }

    /**
     * 添加商品详情图
     * @validate('ProductImageForm')
     */
    public function addProductImage()
    {
        (new ProductImageForm())->goCheck();
        $params = Request::instance()->post()['image'];
        (new ProductImage())->saveAll($params);

//        return writeJson(201, '商品详情图新增成功');
        return Json(new SuccessMessage(['msg'=>'商品详情图新增成功']),201);
    }

    /**
     * 编辑商品详情图
     * @validate('ProductImageForm.edit')
     * @param('image','商品详情图数组','require|array|min:1')
     */
    public function updateProductImage()
    {
        (new ProductImageForm())->sceneEdit()->goCheck();

        $params = Request::instance()->put()['image'];
        (new ProductImage())->saveAll($params);

//        return writeJson(201, '商品详情图编辑成功');
        return Json(new SuccessMessage(['msg'=>'商品详情图编辑成功']),201);
    }

    /**
     * 删除商品详情图
     * @param('ids','待删除的商品详情图id列表','require|array|min:1')
     */
    public function delProductImage()
    {
        $ids = Request::instance()->delete()['ids'];
        (new isPositiveArray())->goCheck();
        ProductImage::destroy($ids);
//        return writeJson(201, '商品详情图删除成功');
        return Json(new SuccessMessage(['msg'=>'商品详情图删除成功']),201);
    }

    /**
     * 添加商品的商品属性
     * @validate('ProductPropertyForm')
     */
    public function addProductProperty()
    {
        (new ProductPropertyForm())->goCheck();

        $params = Request::instance()->post()['property'];
        (new ProductProperty())->saveAll($params);

//        return writeJson(201, '商品属性新增成功');
        return Json(new SuccessMessage(['msg'=>'商品属性新增成功']),201);
    }

    /**
     * 编辑商品属性
     * @validate('ProductPropertyForm.edit')
     */
    public function updateProductProperty()
    {
        (new ProductPropertyForm())->sceneEdit()->goCheck();

        $params = Request::instance()->put()['property'];
        (new ProductProperty())->saveAll($params);

//        return writeJson(201, '商品属性编辑成功');
        return Json(new SuccessMessage(['msg'=>'商品属性编辑成功']),201);
    }

    /**
     * 删除商品属性
     * @param('ids','待删除的商品属性id列表','require|array|min:1')
     */
    public function delProductProperty()
    {
        $ids = Request::instance()->delete()['ids'];
        (new isPositiveArray())->goCheck();
        ProductProperty::destroy($ids);
//        return writeJson(201, '商品属性删除成功');
        return Json(new SuccessMessage(['msg'=>'商品属性删除成功']),201);
    }
}


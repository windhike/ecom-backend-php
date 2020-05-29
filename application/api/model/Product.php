<?php
/**
 * Author: mark m /
 * Date:4/21/2020 6:40 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


use app\api\validate\Count;

class Product extends BaseModel
{
    protected $hidden= ['update_time','delete_time','create_time','pivot','from','main_img_id'];

    protected $autoWriteTimestamp = true;
//    protected $createTime = false; //orderProduct 表没有create time， 设置autowrite后会报内部错误;要去掉createTime
    /**
     * @var bool
     */
    private $status;

    public function getMainImgUrlAttr($url,$data){
        //这个函数是当程序访问Product对象的url属性时（$product->main_img_url），会自动触发。
        //当前代码虽然没有在显式访问main_img_url属性，但是在控制器return $theme时，框架其实访问了$product->main_img_url属性。
        return $this->addImgPrefixToUrl($url,$data);
    }

    public static function getRecentProductList($count)
    {
//        return $this->limit($count)->order('update_time');
        $product = new Product();
        return $product->limit($count)->order('create_time','desc')->select();
    }

    public static function getAllByCategoryId($categoryId)
    {
        return (new Product())->where('category_id','=',$categoryId)->select();
    }

    public function detailImgs(){
        return $this->hasMany('productImage','product_id','id');
    }

    public function properties(){
        return $this->hasMany('productProperty','product_id','id');
    }


    public static function getProductDetail($id){
        return (new Product())->with(['detailImgs','detailImgs.imgUrl','properties',])->select($id);
    }

    public static function getProductsPaginate($params)
    {
        $product = [];
        // 判断是否传递了product_name参数，如果有，构造一个查询条件，按商品名称模糊查询
        if (array_key_exists('product_name', $params)) {
            $product[] = ['name', 'like', '%' . $params['product_name'] . '%'];
        }
        // paginate()方法用于根据url中count和page的参数，计算查询要查询的开始位置和查询数量
        list($start, $count) = paginate();
        // 拿到应用查询条件后的模型实例
        $productList = self::where($product);
        // 调用模型的实例方法count计算该条件下会有多少条记录
        $totalNums = $productList->count();
        // 调用模型的limit方法对记录进行分页并获取查询结果
        $productList = $productList->limit($start, $count)
            ->with('category,properties,detailImgs.imgUrl')
            ->order('create_time desc')
            ->select();
        // 组装返回结果，这里与lin-cms风格保持一致
        $result = [
            // 查询结果
            'collection' => $productList,
            // 总记录数
            'total_nums' => $totalNums
        ];

        return $result;
    }

    public function category()
    {
        // 相对关联
        return $this->belongsTo('Category');
    }

    public function image()
    {
        // 一对多
        // product_image表中有order字段用于图片显示排序
        return $this->hasMany('ProductImage')->order('order');
    }

    public function property()
    {
        // 一对多
        return $this->hasMany('ProductProperty');
    }



}
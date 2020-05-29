<?php
/**
 * Author: mark m /
 * Date:4/22/2020 6:22 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\Theme as ThemeModel;
use app\api\validate\IsIdPositiveInt;
use app\lib\exception\CategoryException;
use app\lib\exception\ProductMissException;
use think\Controller;
use app\api\model\Category as CategoryModel;

class Category extends BaseController
{
    public function getAllCategory(){

        $categoryList = CategoryModel::getCategoryList(); //[],'img'??
        if($categoryList->isEmpty()){
            throw new CategoryException();
        }
        else{
            return $categoryList;
        }
    }

    public function getCategoryProduct($id){
        (new IsIdPositiveInt())->goCheck();

        $result = CategoryModel::getCategoryWithProducts($id);
        if ($result->isEmpty()){  // if theme == null, throw banner miss exception;
            throw new ProductMissException();
        }
        else{
            return $result;
        }
    }
}
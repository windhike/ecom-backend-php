<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
    ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
    ':name' => ['index/hello', ['method' => 'post']],
],

];*/

use think\Route;


Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
Route::get('api/:version/banner','api/:version.Banner/getAllBanner');
Route::post('api/:version/banner','api/:version.Banner/addBanner');
Route::delete('api/:version/banner','api/:version.Banner/deleteBanner');
Route::patch('api/:version/banner/:id','api/:version.Banner/editBannerInfo');
Route::post('api/:version/banner/item','api/:version.Banner/addBannerItem');
Route::put('api/:version/banner/item','api/:version.Banner/editBannerItem');
Route::delete('api/:version/banner/item','api/:version.Banner/delBannerItem');

Route::get('api/:version/theme','api/:version.Theme/getSimpleList'); //?ids=1,2,3
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexProductList',[],['id'=>'\d+']);
Route::post('api/:version/theme','api/:version.Theme/addTheme');
Route::delete('api/:version/theme','api/:version.Theme/delTheme');
Route::patch('api/:version/theme/:id','api/:version.Theme/updateThemeInfo');// 编辑精选主题信息
Route::delete('api/:version/theme/product/:id','api/:version.Theme/removeThemeProduct');// 移除精选主题关联商品
Route::post('api/:version/theme/product/:id','api/:version.Theme/addThemeProduct');// 新增精选主题关联商品


Route::get('api/:version/product/by_category','api/:version.Product/getAllByCategory');
Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
//增加变量规则可以限定只有id是正整数’\d+时才能匹配这个route，这样就不会覆盖下一个route：/product/recent
Route::get('api/:version/product/recent','api/:version.Product/getRecentProducts');
Route::get('api/:version/product/paginate','api/:version.Product/getProductsPaginate');
Route::get('api/:version/product','api/:version.Product/getProducts');
Route::patch('api/:version/product/:id','api/:version.Product/modifyStatus');
Route::post('api/:version/product/','api/:version.Product/addProduct');
Route::delete('api/:version/product/','api/:version.Product/delProduct');
Route::put('api/:version/product/','api/:version.Product/updateProduct');// 更新商品基础信息
Route::post('api/:version/product/image','api/:version.Product/addProductImage');// 新增商品详情图
Route::put('api/:version/product/image','api/:version.Product/updateProductImage');// 编辑商品详情图
Route::delete('api/:version/product/image','api/:version.Product/delProductImage');// 删除商品详情图
Route::post('api/:version/product/property','api/:version.Product/addProductProperty');//
Route::put('api/:version/product/property','api/:version.Product/updateProductProperty');//
Route::delete('api/:version/product/property','api/:version.Product/delProductProperty');//


Route::get('api/:version/cart','api/:version.Cart/getCartData');
Route::post('api/:version/cart','api/:version.Cart/updateCartData'); // uid get by user token


Route::get('api/:version/category/all','api/:version.Category/getAllCategory');
Route::get('api/:version/category/:id','api/:version.Category/getCategoryProduct',[],['id'=>'\d+']);

Route::post('api/:version/token/user','api/:version.Token/getToken');
// token api must be 'post', not 'get';because we can put user's '$code' into 'post' body, more security. Actually the most security way is use 'https'.
Route::post('api/:version/token/app','api/:version.Token/getAppToken');
// token api must be 'post', not 'get';because we can put app's '$ac' '$secrete' into 'post' body, more security. Actually the most security way is use 'https'.
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');// 因为是提交信息，use 'post'
Route::get('api/:version/address', 'api/:version.Address/getUserAddress');

Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/order/:id','api/:version.Order/getOrderDetail',[],['id'=>'\d+']);
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate','api/:version.Order/getSummary');
Route::put('api/:version/order/delivery','api/:version.Order/delivery');
Route::get('api/:version/order','api/:version.Order/getOrders'); //cms get all order by page & date
Route::post('api/:version/order/shipment/:id','api/:version.Order/deliverGoods');// 订单发货,id=order_id
Route::get('api/:version/order/pay/:orderNo','api/:version.Order/getOrderPayStatus');// 查询订单支付状态
Route::post('api/:version/order/pay/refund','api/:version.Order/refund');// 订单退款
Route::get('api/:version/order/pay/refund/:orderNo','api/:version.Order/refundQuery');// 查询退款详情
Route::get('api/:version/order/shipment/record','api/:version.Order/getOrderDeliverRecord');// 查询发货记录


Route::get('api/:version/logistics/:orderNo','api/:version.Logistics/getLogistics');// 物流管理相关接口


Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify','api/:version.Pay/redirectNotify'); // for debug


Route::get('api/:version/user','api/:version.User/getUsersPaginate'); // 查询会员列表
Route::post('api/:version/user/wx_info','api/:version.User/updateUserInfo'); // update User NickName
Route::post('api/:version/user/sms','api/:version.User/sendActivityOnlineSms'); // 发送会员短信

Route::get('api/:version/analysis/order/base','api/:version.Statistics/getOrderBaseStatistics'); // 时间范围统计订单数据
Route::get('api/:version/analysis/user/base','api/:version.Statistics/getUserBaseStatistics'); // 时间范围统计新增会员数



Route::post('api/cms/user/login','api/v1.Token/getAppToken');// image 文件 upload
Route::post('api/cms/file/image','api/v1.ImageFile/imageUpload');// image 文件 upload
/*Route::group('api', function () {
    Route::group('cms', function () {
        // 账户相关接口分组
        Route::group('user', function () {
            // 登陆接口
//            Route::post('login', 'api/cms.User/login');
            Route::post('login', 'api/v1.Token/getAppToken');
            // 刷新令牌
            Route::get('refresh', 'api/cms.User/refresh');
            // 查询自己拥有的权限
            Route::get('auths', 'api/cms.User/getAllowedApis');
            // 注册一个用户
            Route::post('register', 'api/cms.User/register');
            // 更新头像
            Route::put('avatar','api/cms.User/setAvatar');
            // 查询自己信息
            Route::get('information','api/cms.User/getInformation');
            // 用户更新信息
            Route::put('','api/cms.User/update');
            // 修改自己密码
            Route::put('change_password','api/cms.User/changePassword');
        });
        // 管理类接口
        Route::group('admin', function () {
            // 查询所有权限组
            Route::get('group/all', 'api/cms.Admin/getGroupAll');
            // 查询一个权限组及其权限
            Route::get('group/:id', 'api/cms.Admin/getGroup');
            // 删除一个权限组
            Route::delete('group/:id', 'api/cms.Admin/deleteGroup');
            // 更新一个权限组
            Route::put('group/:id', 'api/cms.Admin/updateGroup');
            // 新建权限组
            Route::post('group', 'api/cms.Admin/createGroup');
            // 查询所有可分配的权限
            Route::get('authority', 'api/cms.Admin/authority');
            // 删除多个权限
            Route::post('remove', 'api/cms.Admin/removeAuths');
            // 添加多个权限
            Route::post('/dispatch/patch', 'api/cms.Admin/dispatchAuths');
            // 查询所有用户
            Route::get('users', 'api/cms.Admin/getAdminUsers');
            // 修改用户密码
            Route::put('password/:uid', 'api/cms.Admin/changeUserPassword');
            // 删除用户
            Route::delete(':uid', 'api/cms.Admin/deleteUser');
            // 更新用户信息
            Route::put(':uid', 'api/cms.Admin/updateUser');

        });
        // 日志类接口
        Route::group('log',function (){
            Route::get('', 'api/cms.Log/getLogs');
            Route::get('users', 'api/cms.Log/getUsers');
            Route::get('search', 'api/cms.Log/getUserLogs');
        });
        //上传文件类接口
        Route::post('file','api/cms.File/postFile');
    });
});*/




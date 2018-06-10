<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

// api/v1.Banner/getBanner  三段式，模块名/控制器名/方法名(v1.Banner v1下面的Banner，用点连接)
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');

Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');


//Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
////   ['id'=>'\d+] id为正整数的正则表达式，当id为正整数时，走 api/:version/product/:id 这条路由
//Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
//Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');

//路由分组
Route::group('api/:version/product', function () {
    Route::get('/by_category', 'api/:version.Product/getAllInCategory');
    //   ['id'=>'\d+] id为正整数的正则表达式，当id为正整数时，走 api/:version/product/:id 这条路由
    Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});

Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

Route::post('api/:version/token/user', 'api/:version.Token/getToken');

Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

Route::post('api/:version/order/','api/:version.Order/placeOrder');
Route::post('api/:version/order/by_user','api/:version.Order/getSummaryByUser');

Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');

//给微信回调的一个API地址，微信将访问这个地址，从而实现回调通知
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');
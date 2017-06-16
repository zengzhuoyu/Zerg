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

use think\Route;

//获取指定id的banner信息
//Route::get('api/v1/banner/:id','api/v1.Banner/getBanner');
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

//所有专题
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');

//专题详情
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

//商品 - 路由分组
Route::group('api/:version/product',function(){//参数二是闭包函数

    //分类中的商品
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    //商品详情
    Route::get('/:id','api/:version.Product/getOne',[],['id' => '\d+']);
    //最近新品
    Route::get('/recent','api/:version.Product/getRecent');

});

//所有分类
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//
Route::post('api/:version/token/user','api/:version.Token/getToken');

//创建或者更新地址
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');

//下单
Route::post('api/:version/order','api/:version.Order/placeOrder');



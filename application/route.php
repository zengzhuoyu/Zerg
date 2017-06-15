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

//最近新品
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//分类中的商品
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');

//所有分类
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//
Route::post('api/:version/token/user','api/:version.Token/getToken');

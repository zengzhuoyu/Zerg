<?php

namespace app\api\model;

//use think\Db;
//use think\Exception;
use think\Model;

class Banner extends Model
{
//	public static function getBannerById($id)
//	{
//        //原生sql
////        $result = Db::query('select * from banner_item where banner_id=?',[$id]);
////        return $result;
//
//        //查询构造器
////        $result = Db::table('banner_item')->where('banner_id',$id)->select();
//        //闭包写法
//        $result = Db::table('banner_item')
//            ->where(function ($query) use($id){
//                $query->where('banner_id',$id);
//            })
//            ->select();
//        return $result;
//	}
}
<?php

namespace app\api\model;

use think\Model;

class Product extends BaseModel
{
    protected $hidden = [
        'delete_time','main_img_id','pivot',
        'from','category_id','create_time',
        'update_time'
    ];

    public function getMainImgUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }

    public static function getMostRecent($count)
    {
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();

        return $products;
    }

    public static function getProductsByCategoryID($categoryID)
    {
        $products = self::where('category_id','=',$categoryID)
            ->select();

        return $products;
    }

    public function imgs()
    {
        return $this->hasMany('ProductImage','product_id','id');
    }

    public function properties()
    {
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public static function getProductDetail($id)
    {
        //Query
        $product = self::with([
            'imgs' => function($query){//闭包函数
                $query->with(['imgUrl'])
                ->order('order','asc');
            }
        ])
            ->with(['properties'])//链式方法的with可以连接多个
            ->find($id);

        return $product;
    }
}
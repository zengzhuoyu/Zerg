<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 9:46
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;

class Category
{

    public function getAllCategories()
    {
        $categories = CategoryModel::all([],'img');
        // ==
//        $categories = CategoryModel::with('img')->select();

        if($categories->isEmpty()){
            throw new CategoryException();
        }

        return $categories;
    }
}
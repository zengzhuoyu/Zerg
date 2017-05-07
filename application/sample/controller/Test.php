<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 20:51
 */

namespace app\sample\controller;

use think\Request;

class Test {

//    public function hello($id,$name,$age)
    public function hello(Request $request)
    {
//        return 'Hello ~';

//        $id = Request::instance()->param('id');
//        $name = Request::instance()->param('name');
//        $age = Request::instance()->param('age');
//
//        echo $id;
//        echo "|";
//        echo $name;
//        echo "|";
//        echo $age;

//        var_dump($all = Request::instance()->param());

//        var_dump($all = Request::instance()->get());

//        var_dump($all = Request::instance()->route());

//        var_dump($all = Request::instance()->post());

//        var_dump($all = input('param.'));

//        echo $all = input('param.name');

//            var_dump($all = input('route.id'));

        var_dump($all = $request->param());
    }
}
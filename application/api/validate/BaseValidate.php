<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 11:45
 */

namespace app\api\validate;


use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate{

    public function goCheck()
    {

        //获取所有的http传入参数
        $request = Request::instance();
        $params = $request->param();

        //对这些参数做校验
        $result = $this->check($params);

        if(!$result){
            $error = $this->error;
            throw new Exception($error);
        }else{
            return true;
        }
    }
}
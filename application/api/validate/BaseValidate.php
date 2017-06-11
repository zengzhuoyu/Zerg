<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 11:45
 */

namespace app\api\validate;

use app\lib\exception\BaseException;
use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{

    public function goCheck()
    {

        //获取所有的http传入参数
        $request = Request::instance();
        $params = $request->param();

        //对这些参数做校验
//        $result = $this->check($params);
        //批量验证
        $result = $this->batch()->check($params);

        if(!$result){
//            $error = $this->error;
//            throw new BaseException($error);

            $e = new ParameterException([
                'msg' => $this->error
            ]);

//            //外部访问成员变量的方式不可取
//            $e->msg = $this->error;
//            $e->errorCode = 10002;

            throw $e;
        }else{
            return true;
        }
    }

    protected function isPositiveInteger($value,$rule = '',$data = '',$field = '')
    {
        //验证正整数规则
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }

        //自定义错误返回信息
        return false;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 11:20
 */

namespace app\api\validate;

class IDMustBePositiveInt extends BaseValidate
{

    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'num' => 'in:1,2,3'
    ];

    protected function isPositiveInteger($value,$rule = '',$data = '',$field = '')
    {
        //验证正整数规则
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }else{
            //自定义错误返回信息
            return $field.'必须是正整数';
        }
    }
}
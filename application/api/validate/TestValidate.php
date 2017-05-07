<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 10:27
 */

namespace app\api\validate;


use think\Validate;

class TestValidate extends Validate{

    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'email'
    ];
}
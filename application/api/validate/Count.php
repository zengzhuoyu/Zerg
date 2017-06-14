<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 11:45
 */

namespace app\api\validate;

class Count extends BaseValidate
{

    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];
}
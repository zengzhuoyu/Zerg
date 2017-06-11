<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 11:20
 */

namespace app\api\validate;

class IDCollection extends BaseValidate
{

    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须为以逗号分隔的多个正整数,仔细看文档啊'
    ];

    //ids = id1,id2,id3.......
    protected function checkIDs($value)
    {
        $values = explode(',',$value);
        if(empty($values)){
            return false;
        }
        foreach($values as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }

        return true;
    }
}
<?php

namespace app\api\model;

use think\Model;

class Image extends BaseModel
{
    protected $hidden = [
        'id',
        'from',
        'update_time',
        'delete_time'
    ];

    public function getUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }

}
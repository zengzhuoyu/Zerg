<?php

namespace app\api\model;

use think\Model;

class Category extends BaseModel
{
    protected $hidden = [
        'delete_time',
        'update_time',
        'create_time'
    ];

    public function img()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }
}
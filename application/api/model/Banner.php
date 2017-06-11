<?php

namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{
    protected $hidden = [
        'delete_time',
        'update_time'
    ];

    public function items()
    {
        return $this->hasMany('BannerItem','banner_id','id');//关联的模型、关联模型的外键、当前模型的主键
    }

    public static function getBannerById($id)
    {
        $banner = self::with(['items','items.img'])
            ->find($id);

        return $banner;
    }
}
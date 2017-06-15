<?php

namespace app\api\model;

class User extends BaseModel
{
    public static function getByOpenID($openid)
    {
        $user = self::wherer('openid','=',$openid)
            ->find();

        return $user;
    }
}
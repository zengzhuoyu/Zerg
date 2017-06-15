<?php

    return [

        //  +---------------------------------
        //  微信相关配置
        //  +---------------------------------

        // 小程序app_id
        'app_id' => 'wx1810f0bf55cf58a8',
        // 小程序app_secret
        'app_secret' => '274e80c4e2f2efffc92f552a246563df',

        // 微信使用code换取用户openid及session_key的url地址 %s是占位符
        'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
            "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    ];
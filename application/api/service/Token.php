<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 10:56
 */

namespace app\api\service;

use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);

        //自定义规则加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');

        return $uid;
    }

    public static function getCurrentTokenVar($key)
    {
        //用户携带的token放在http的header里
        $token = Request::instance()
            ->header('token');

        $vars = Cache::get($token);

        //获取失效原因：缓存失效或者缓存系统有问题
        if(!$vars){
            throw new TokenException();
        }else{
            //转换成数组
            //使用redis缓存的话，获取的直接是数组
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                //不是返回到客户端的异常
                throw new Exception('尝试获取的Token变量并不存在');
            }

        }
    }
}
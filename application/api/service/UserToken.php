<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 10:56
 */

namespace app\api\service;

use app\lib\exception\TokenException;
use think\Exception;

use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);//true 表示转换成数组

        //微信返回的结果如果是错误的，结果会为空
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            //返回错误，会存在一个参数errcode
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }

        $cacaheValue = $this->prepareCacheValue($wxResult,$uid);
        $token = $this->saveToCache($cacaheValue);
        return $token;

    }

    //写入缓存
    private function saveToCache($cacaheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacaheValue);//数组对象转换成字符串

        //令牌失效时间转换成缓存失效时间
        $expire_in = config('setting.tokne_expire_in');

        //使用tp5自带缓存 写入缓存
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;
    }

    private function prepareCacheValue($wxResult,$uid)
    {
        $cacaheValue = $wxResult;
        $cacaheValue['uid'] = $uid;
        $cacaheValue['scope'] = 16;

        return $cacaheValue;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);

        return $user->id;
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/14
 * Time: 11:12
 */

namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception
{

    //http 状态吗：404，200等
    public $code = 400;//一般得用private或者protected

    //错误具体信息
    public $msg = '参数错误';//一般写英文

    //自定义错误码
    public $errorCode = 10000;

    public function __construct(array $params = [])
    {
//        if(!is_array($params)){
//            return;
//            //或者
////            throw new Exception('参数必须是数组');
//        }

        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }


}
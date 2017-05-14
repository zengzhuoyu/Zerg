<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/14
 * Time: 11:12
 */

namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception{

    //http 状态吗：404，200
    public $code = 400;//一般得用private或者protected

    //错误具体信息
    public $msg = '参数错误';//一般用英文

    //自定义错误码
    public $errorCode = 10000;


}
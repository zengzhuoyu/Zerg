<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/14
 * Time: 11:07
 */

namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;//继承tp5异常处理类
use think\Request;

class ExceptionHandler extends Handle{

    private $code;
    private $msg;
    private $errorCode;
    //返回客户端当前请求的url路径

    //重写错误返回的格式
    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            $this->code = 500;
            $this->msg = '服务器内部错误，不想告诉你';
            $this->errorCode = 999;//需要写进api文档
        }

        $request = Request::instance();

        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];

        return json($result,$this->code);

    }
}
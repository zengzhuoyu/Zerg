<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/14
 * Time: 11:18
 */

namespace app\lib\exception;

class ThemeMissException extends BaseException
{

    public $code = 404;

    public $msg = '指定专题不存在，请检查专题ID';

    public $errorCode = 30000;
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/14
 * Time: 11:18
 */

namespace app\lib\exception;

class CategoryException extends BaseException
{

    public $code = 404;

    public $msg = '指定分类不存在，请检查参数';

    public $errorCode = 50000;
}
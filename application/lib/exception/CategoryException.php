<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/10
 * Time: 17:39
 */
namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定类目不存在，请检查参数';
    public $errorCode = 50000;
}
<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/9
 * Time: 11:45
 */
namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定的主题不存在';
    public $errorCode = '30000';
}
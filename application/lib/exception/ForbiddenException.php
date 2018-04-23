<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/18
 * Time: 21:59
 */
namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}
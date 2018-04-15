<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/12
 * Time: 14:07
 */
namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}
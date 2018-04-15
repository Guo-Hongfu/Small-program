<?php
namespace app\lib\exception;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 17:43
 */
class ParameterException extends BannerMissException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}
<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 16:04
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的banner不存在';
    public $errorCode = '';
}
<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/22
 * Time: 20:53
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}
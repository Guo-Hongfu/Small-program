<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/10
 * Time: 16:50
 */
namespace app\lib\exception;


class ProductException extends BaseException
{
    //HTTP 状态码 404,200
    public $code = 404;

    // 错误具体信息
    public $msg = '指定的商品不存在，请检查参数';

    //自定义的错误码
    public $errorCode = 20000;
}
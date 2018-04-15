<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/11
 * Time: 16:28
 */

namespace app\lib\exception;


class WxChatException extends BaseException
{
    //HTTP 状态码 404,200
    public $code = 400;

    // 错误具体信息
    public $msg = '微信服务器接口调用失败';

    //自定义的错误码
    public $errorCode = 999;
}
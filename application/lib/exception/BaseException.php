<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 16:02
 */

namespace app\lib\exception;

class BaseException extends \Exception
{
    //HTTP 状态码 404,200
    public $code = 400;

    // 错误具体信息
    public $msg = '参数错误';

    //自定义的错误码
    public $errorCode = 10000;

    public function __construct($params = []) {
        if (!is_array($params)){
             return ; //这个写法是不强制更改成员变量的值
//            throw new Exception('参数必须是数组');
        }
        //判断传入的数组中是否有code
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}
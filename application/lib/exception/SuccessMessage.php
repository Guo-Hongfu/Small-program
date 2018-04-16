<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 23:20
 */
namespace app\lib\exception;


class SuccessMessage extends BaseException
{
   public $code = 201;
   public $msg = 'ok';
   public $errorCode = 0;
}
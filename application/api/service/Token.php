<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/12
 * Time: 13:45
 */
namespace app\api\service;


class Token
{
    //生成Token
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChars(32);
        //用三组字符串，进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT']; //时间戳
        //salt 盐
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }
}
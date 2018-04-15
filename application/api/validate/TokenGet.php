<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/11
 * Time: 15:15
 */
namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => '没有code，无法获取token!'
    ];
}
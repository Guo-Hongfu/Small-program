<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/10
 * Time: 22:26
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

     protected $message = [
         'page' => '分页参数必须是正整数',
         'size' => '分页参数必须是正整数'
     ];
}
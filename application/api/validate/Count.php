<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/10
 * Time: 16:38
 */
namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15',
    ];

    protected $message = [
        'count' => '传入的count必须是正整数并且要在1和15之间'
    ];
}
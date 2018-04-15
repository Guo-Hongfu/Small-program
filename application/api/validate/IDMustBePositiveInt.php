<?php
namespace app\api\validate;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 13:39
 */

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'num' => 'in:1,2,3'
    ];
    protected  $message = [
      'id' => 'id必须是正整数'
    ];


}
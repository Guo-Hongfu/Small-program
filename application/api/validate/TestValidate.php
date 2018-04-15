<?php
namespace app\api\validate;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 11:46
 */

use think\Validate;

class TestValidate extends Validate
{
    protected $rule = [
      'name' =>'require|max:10'
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/14
 * Time: 22:12
 */
namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name'     => 'require|isNotEmpty',
        'mobile'   => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city'     => 'require|isNotEmpty',
        'country'  => 'require|isNotEmpty',
        'detail'   => 'require|isNotEmpty'
    ];
    protected $scene = [
        'add' => ['name','mobile']
    ];
}
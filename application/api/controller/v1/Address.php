<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/14
 * Time: 22:10
 */
namespace app\api\controller\v1;


use app\api\validate\AddressNew;

class Address
{
    /**
     * 创建更新用户收货地址
     */
    public function createOrUpdateAddress(){
        (new AddressNew())->goCheck();
        // 根据Token来获取uid
        // 根据uid来查找用户数据，判断用户是否存在，如果不存在抛出异常

    }
}
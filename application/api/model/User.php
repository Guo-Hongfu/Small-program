<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/11
 * Time: 15:23
 */
namespace app\api\model;


class User extends BaseModel
{
    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)
            ->find();
        return $user;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/11
 * Time: 15:14
 */
namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    /**
     * @param string $code
     * @return array
     * post
     *http://www.tp5.com/api/v1/token/user?code=dada
     */
    public function getToken($code = '') {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get(); //get 返回一个字符串
        return [
          'token' => $token
        ]; // 关联数组 会被框架默认序列化成json格式
    }
}
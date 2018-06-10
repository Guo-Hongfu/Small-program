<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/11
 * Time: 15:42
 */

return [
    'app_id' => 'wx56c30639bd30ed18',
//    'app_secret' => '7dc0dcb9569a58b55146454ed615871a', //之前的
    'app_secret' => '9abf32f0ccc001eaae49f50806341f26',
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?'
        . "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];
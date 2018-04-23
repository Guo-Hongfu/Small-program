<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/11
 * Time: 15:24
 * model分层
 * service层处理较为复杂的业务逻辑，在model之上的
 * model 处理简单 的细腻的业务逻辑,还复杂调用数据访问层，实现对数据的增删改查
 */
namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WxChatException;
use think\Exception;
use app\api\model\User as UserModel;
class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code) {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        //sprintf函数，参数替换占位符。
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get() {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);//加了true,把字符串改变成数组。没有true，就是一个对象
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，检查appid，微信内部错误');
        }else{
            //要是接口调用有问题，微信会返回这样的一个errcode码
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);

            }
        }
    }
    //颁发令牌
    private function grantToken($wxResult){
        // 拿到openid
        // 数据库里看一下，这个openid是不是已经存在。
        // 如果存在，则不处理，如果不存在那么新增一条user记录
        // 生成令牌，准备缓存数据，写入缓存
        // 把令牌返回到客户端去
        // 缓存 key: 令牌，value: wxResult,uid,scope(scope这个参数是权限)
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }
    //写入缓存
    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue); //把数组转换成json编码
        $expire_in = config('setting.token_expire_in');//缓存有效时间
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }
    //构造缓存数据
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        // scope = 16 代表App用户的权限数值
        $cachedValue['scope'] = ScopeEnum::User;
        // scope = 32 代表CMS(管理员)用户的权限数值
//        $cachedValue['scope'] = 32;
        return $cachedValue;

    }
    //如果没有openid，就新增一条记录的方法
    private function newUser($openid){
        $user = UserModel::create([
           'openid' => $openid
        ]);
        return $user->id;
    }
    //接口异常，
    private function processLoginError($wxResult){
        throw new WxChatException([
                'msg' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]);
    }

}
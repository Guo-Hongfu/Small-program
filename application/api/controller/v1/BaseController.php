<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/20
 * Time: 21:03
 */
namespace app\api\controller\v1;


use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    //需要用户和CMS管理员都可以访问的权限
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }
    //只有用户才能访问的接口权限
    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }
}
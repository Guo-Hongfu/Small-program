<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/8
 * Time: 22:08
 */

namespace app\api\controller\v1;


use app\api\validate\IDMustBePositiveInt;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    /**
     * 请求预订单信息
     * API到微信服务器 生成的一个订单
     * 微信需要什么参数，在微信支付开发文档 统一下单文档中
     * 客户端需要传订单号，
     * @param string $id
     */
    public function getPreOrder($id=''){
        (new IDMustBePositiveInt())->goCheck();

    }
}
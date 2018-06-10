<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/8
 * Time: 22:08
 */

namespace app\api\controller\v1;


use app\api\service\WxNotify as WxNotifyService;
use app\api\service\WxPay as WxPayService;
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
     * @return array
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new WxPayService($id);
        return $pay->pay();
    }

    public function receiveNotify()
    {
        //接收微信通知的接口
        // 通知频率为 15/15/30/180/1800/1800/1800/3600 单位:秒
        // 微信不能绝对的保证每一次回调都能成功的发送到服务器中(微信支付文档中有说)

        // 1.检测库存，防止超卖
        // 2, 更新订单表中这个订单的status状态
        // 3. 减库存
        // 如果成功处理，我们返回微信成功处理的消息.否则，我们需要返回没有成功处理

        //特点:post; 微信返回的数据格式是xml格式; 路由地址后面不能用问号(?)携带参数的
        $notify = new WxNotifyService();
        $notify->Handle();
    }
}
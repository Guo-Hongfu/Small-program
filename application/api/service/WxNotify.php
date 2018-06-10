<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/10
 * Time: 19:46
 */
namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\Product as ProductModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        // data 已经被微信提供的SDK转换成数组了
        //支付成功
        // data 参数参考微信支付文档
        // 事务,锁
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();//开启事务
            try {
                // 从订单表中查出这条数据
                $order = OrderModel::where('order_no', '=', $orderNo)
                    ->lock(true) // 加了lock 就是加了锁
                    ->find();
                if ($order->status == 1) {
                    //检测库存
                    $orderService = new OrderService();
                    $stockStatus = $orderService->checkOrderStock($order->id);
                    // if通过检测,,有库存
                    if ($stockStatus['pass']) {
                        //微信支付成功，订单状态更新已支付
                        $this->updateOrderStatus($order->id, true);
                        //减库存
                        $this->reduceStock($stockStatus);
                    } else {
                        // 微信支付成功了，但是没有通过检测，没有库存，
                        //只修改订单状态 (支付成功，但是没有库存)
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db::commit();//提交事务
                return true;
            } catch (\Exception $e) {
                Db::rollback();//回滚事务
                Log::error($e);
                return false;
            }
        }else{
            // 支付失败,
            // 返回微信为 true, 知晓微信支付失败了都，就不要微信返回失败的通知了。
            return true;
        }
    }

    /**
     * 消减库存量
     * @param $stockStatus
     * array $stockStatus 该订单的信息，
     * $stockStatus['pStatusArray']包含下单的产品的信息
     *
     */
    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            ProductModel::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
    }

    /**
     * @param $orderID
     * @param $success
     * 更新订单状态
     */
    private function updateOrderStatus($orderID, $success)
    {
        $status = $success ?
            OrderStatusEnum::PAID :
            OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }
}
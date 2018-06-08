<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/8
 * Time: 22:23
 */
namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use think\Exception;
use app\api\service\Order as OrderService;
class WxPay
{
    //定义两个私有成员变量，订单id和订单号
    private $orderID;
    private $orderNO;

    //构造函数
    function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不允许为空');
        }
        $this->orderID = $orderID;
    }

    //支付主方法
    public function pay(){
        //订单号可能不存在，还要检测订单是否存在。(检测订单是否存在)
        //订单号确实是存在的，但是订单号和当前用户是不匹配的。(检测订单和用户是否匹配)
        //订单有可能被支付 (订单状态)
        //检测库存 根据订单id检测库存量 (库存检测)
        //
        // 这4个检测，并没有先后顺序
        //但是有原则：
        //1.最有可能发生的情况的检测放在前面，一旦被检测就出来就不会有后续的步骤了。节约服务器性能
        //2.从本身代码消耗服务器性能考虑，很明显，这次的库存量检测所消耗的服务性能更多些，
        //  所以可以把库存检测放在最后面。把服务器性能消耗最小的检测放在前面
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);

    }

    /**
     * @throws OrderException
     * 检测订单是否存在
     */
    private function checkOrderValid(){
        $order = OrderModel::where('id','=',$this->orderID)
            ->find();
        if (!$order){
            //订单不存在
            throw new OrderException();
        }
    }

}
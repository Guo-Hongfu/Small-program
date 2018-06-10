<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/8
 * Time: 22:23
 */
namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use think\Loader;
use think\Log;

//   extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
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
        //1.订单号可能不存在，还要检测订单是否存在。(检测订单是否存在)
        //2.订单号确实是存在的，但是订单号和当前用户是不匹配的。(检测订单和用户是否匹配)
        //3.订单有可能被支付 (订单状态)
        //4.检测库存 根据订单id检测库存量 (库存检测)
        //
        // 这4个检测，并没有先后顺序
        //但是有原则：
        //一.最有可能发生的情况的检测放在前面，一旦被检测就出来就不会有后续的步骤了。节约服务器性能
        //二.从本身代码消耗服务器性能考虑，很明显，这次的库存量检测所消耗的服务性能更多些，
        //  所以可以把库存检测放在最后面。把服务器性能消耗最小的检测放在前面

        // 检测前3点
        $this->checkOrderValid();
        $orderService = new OrderService();
        //检测库存量
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    // 微信预订单
    private function makeWxPreOrder($totalPrice){
        //openid
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        //订单总金额,微信是以分作单位的
        $wxOrderData->SetTotal_fee($totalPrice*100);
        // SetBody 商品的一个简要的描述，根据自己的项目定义
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        //用于接收微信回调的通知 url 地址,很重要
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        return $this->getPaySignTrue($wxOrderData);

    }

    private function getPaySignTrue($wxOrderData){
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'Success' ){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');

        }
        //prepay_id
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    /**
     * @param $wxOrder
     * 对微信返回的结果中的prepay_id 存入对应的订单表中
     */
    private function recordPreOrder($wxOrder){
        // 把微信返回来的结果($wxOrder)中的 prepay_id 存在对应的订单表中
        OrderModel::where('id','=',$this->orderID)
            ->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    /**
     * 小程序的微信支付
     */
    private function sign($wxOrder){
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        //时间戳 字符串类型
        $jsApiPayData->SetTimeStamp((string)time());
        //生成随机字符串
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id = '.$wxOrder['prepay_id']);
        //设置签名类型
        $jsApiPayData->SetSignType('md5');
        //生成签名
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        //不把appid返回到客户端去
        unset($rawValues['appId']);

        return $rawValues;
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
        //检测订单和用户是否匹配
        if (!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

}
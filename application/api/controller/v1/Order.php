<?php
namespace app\api\controller\v1;
/**use app\lib\enum\ScopeEnum;
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/19
 * Time: 22:48
 */


use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;


class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'placeOrder']
    ];


    // 用户在选择商品后，向API提交包含它所选择商品的相关信息
    // API在接受到信息后，需要检查订单相关商品的库存量
    // 有库存，把订单数据存入数据库中。下单成功了，返回客户端消息，告诉客户端可以支付了
    // 调用我们的支付接口，进行支付
    // 还需要再次进行库存量检测
    // 服务器这边就可以调用微信的支付接口进行支付
    // 小程序会根据服务器返回的结果拉起微信支付
    // 微信会返回给我们一个支付的结果。（异步）
    // 成功，也需要进行库存量的检测
    // 成功：进行库存量的扣除，
    public function placeOrder(){
        //cms管理员账号没有权限调用下单接口。
        (new OrderPlace())->goCheck();
        //后面加了 /a 获取products数组
        $products = input('post.products/a');
//        $products=[
//            '0' =>[
//                'product_id' => 1,
//                'count'   => 2,
//
//            ],
//            '1' =>[
//                'product_id' => 3,
//                'count'   => 3
//            ],
//        ];
//        $uid =1 ;
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }

    //查询用户的订单的简要信息
    public function getSummaryByUser($page=1,$size=15){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if($pagingOrders->isEmpty()){
            return [
                'data' => [],
                //获取当前页码
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        return [
            'data' => $pagingOrders->toArray(),
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }
}
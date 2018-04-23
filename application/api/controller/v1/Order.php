<?php
/**use app\lib\enum\ScopeEnum;
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/19
 * Time: 22:48
 */
namespace app\api\controller\v1;

use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
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
    // 微信会返回给我们一个支付的结果。（异步）
    // 成功，也需要进行库存量的检测
    // 成功：进行库存量的扣除，
    public function placeOrder(){
        //cms管理员账号没有权限调用下单接口。
        (new OrderPlace())->goCheck();
        //后面加了 /a 获取products数组
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
    }
}
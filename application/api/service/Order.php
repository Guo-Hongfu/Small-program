<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/21
 * Time: 22:53
 */
namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
class Order
{
    //订单的商品列表，也就是客户端传递过来的products参数
    protected $oProducts;

    // 真实的商品信息（包括库存量）
    protected $products;

    protected $uid;

    public function place($uid, $oProducts)
    {
        //oProducts 和 products 进行对比
        //products 从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $this->uid = $uid;

        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            // 没有库存，创建订单失败，给一个订单编号为 -1
            $status['order_id'] = -1;
            return $status;
        }
        //开始创建订单

        //订单快照
        $orderSnap = $this->snapOrder($status);
        //创建订单
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;

    }
    //多对对关联，假如一方是要先存在的，那这个多对多就拆分开一对多的关系
    //创建订单
    private function createOrder($snap){
        try {
            //生成订单号
            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            //订单id
            $orderID = $order->id;
            //下单时间
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
                //在oProducts中新增了order_id
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }catch (\Exception $e){
            throw $e;
        }
    }

    //生成重复率非常小的订单号
    public static function makeOrderNo(){
        $yCode = array('A','B','C','D','E','F','G','H','I','J');
        $orderSn=
            $yCode[intval(date('Y'))-2018].strtoupper(dechex(date('m'))).date(
                'd').substr(time(),-5).substr(microtime(),2,5).sprintf(
                    '%02d'.rand(0,99));
        return $orderSn;
    }
    /**
     * 生成订单快照
     * @param $status
     */
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            //商品的总数量
            'totalCount' => 0,
            'pStatus' => [],
            //用户收货地址
            'snapAddress' => null,
            //订单名称
            'snapName' => '',
            'snapImg' => ''
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', '=', $this->uid)->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001,
            ]);
        }
        return $userAddress->toArray();
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'], $oProduct['count'], $this->products
            );
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            //该订单的总价 == 每个产品的总价之和
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];
        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
            if ($pIndex == -1) {
                throw new OrderException([
                    'msg' => 'id为' . $oPID . '的商品不存在，创建订单失败',
                ]);
            } else {
                $product = $products[$pIndex];
                $pStatus['id'] = $product['id'];
                $pStatus['name'] = $product['name'];
                $pStatus['count'] = $oCount;
                //当前商品的总价
                $pStatus['totalPrice'] = $product['price'] * $oCount;
                if ($product['stock'] - $oCount >= 0) {
                    //当前库存量减去oCount 大于等于0，表示有库存
                    $pStatus['haveStock'] = true;
                }
            }
            return $pStatus;
        }
    }

    //根据订单信息查找真实的商品信息
    private function getProductByOrder($oProducts)
    {
        //不要循环的查询数据库
        //用下面这种循环出id,
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        $products = Product::where('id','in',$oPIDs)
            ->field(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->select();
        $products=collection($products)->toArray();
        return $products;
    }
}
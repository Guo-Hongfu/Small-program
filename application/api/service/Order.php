<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/4/21
 * Time: 22:53
 */
namespace app\api\service;


use app\api\model\Product;
use app\lib\exception\OrderException;

class Order
{
    //订单的商品列表，也就是客户端传递过来的products参数
    protected $oProducts;

    // 真是的商品信息（包括库存量）
    protected $products;

    protected $uid;

    public function place($uid,$oProducts){
        //oProducts 和 products 进行对比
        //products 从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->products =$this->getProductByOrder($oProducts);
        $this->uid = $uid;
    }

    private function getOrderStatus(){
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct){
            
        }
    }
    private function getProductStatus($oPID,$count,$products){
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count'  => 0,
            'name' => '',
            'totalPrice' => 0
        ];
        for($i = 0; $i<$count($products);$i++){
            if ($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
            if ($pIndex == -1){
                throw new OrderException();
            }
        }
    }

    //根据订单信息查找真实的商品信息
    private function getProductByOrder($oProducts){
        //不要循环的查询数据库
        //用下面这种循环出id,
        $oPIDs = [];
        foreach ($oProducts as $item){
            array_push($oPIDs,$item['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id','price','sock','name','main_img_url'])
            ->toArry();
        return $products;
    }
}
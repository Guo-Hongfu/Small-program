<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/10
 * Time: 16:34
 */
namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    /**
     * 获取最近上架的商品
     * @param int $count
     * @return string
     * @throws ProductException
     * @count 获取传入的最近新品数，默认15条
     * @url /product/recent?count=$count
     * http://www.tp5.com/api/v1/product/recent?$count=11
     * * http://www.tp5.com/api/v1/product/recent 默认$count = 15
     * GET
     */
    public function getRecent($count=15){
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);

        if ($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    /**
     * @param $id
     * @return mixed
     * @throws ProductException
     * //获取一个分类下的所有产品
     * @url /product/by_category?id=$id
     * http://www.tp5.com/api/v1/product/by_category?id=2
     * GET
     */
    public function getAllInCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductByCategory($id);
        if ($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws ProductException
     * //获取商品详情页
     * @url /product/$id
     * GET
     */
    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/9
 * Time: 10:17
 */
namespace app\api\model;


class Product extends BaseModel
{
    //pivot 是TP5 多对多 自动带的中间字段
    protected $hidden = [
        'category_id', 'main_img_id', 'from',
        'pivot', 'create_time', 'delete_time', 'update_time'
    ];

    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    //一对多的关系 产品详情页的图片
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    //产品参数模型关联，一对多关联
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    public static function getMostRecent($count)
    {
        $product = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $product;
    }

    public static function getProductByCategory($categoryID)
    {
        $products = self::where('category_id', '=', $categoryID)
            ->select();
        return $products;
    }

    //获取产品的详情页
    public static function getProductDetail($id)
    {
        $product = self::with([
            //关联嵌套模型下的闭包函数构造器
            //产品详情页下的image进行order正序排序
            'imgs' => function ($query) {
                $query->with(['imgUrl'])
                    ->order('order', 'asc');
            }
        ])
            ->with(['properties'])
            ->find($id);
        return $product;
    }
}
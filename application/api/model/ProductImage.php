<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/12
 * Time: 16:29
 */
namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = [
        'img_id','delete_time','product_id'
    ];
    //定义和image模型关系，一对一的关系。
    //因为product_image表中有外键，所以用belongsTo
    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/12
 * Time: 16:35
 */
namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = [
      'product_id','delete_time','id'
    ];

}
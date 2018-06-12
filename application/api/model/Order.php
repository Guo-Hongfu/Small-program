<?php
/**
 * Created by PhpStorm.
 * User: Guo-Hongfu
 * Date: 2018/6/6
 * Time: 22:25
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time','update_time'];
    protected $autoWriteTimestamp=true;
    //读取器
    //订单查询的时候， 把存进order表中的snap_items字段(以json格式存的:它叫json字符串)给json解码出来(json对象)
    // 注意: json_decode 参数没有加 true
    public function getSnapItemsAttr($value){
        if (empty($value)){
            return null;
        }
        return json_decode($value);
    }
    // 读取器，意思和 getSnapItemsAttr 一样，只是字段不一样，
    public function getSnapAddressAttr($value){
        if (empty($value)){
            return null;
        }
        return json_decode($value);
    }

    // 订单查询分页处理
    public static function getSummaryByUser($uid,$page=1,$size=15){
        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
}
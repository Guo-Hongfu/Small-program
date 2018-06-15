<?php
namespace app\api\model;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 15:04
 */

class Banner extends BaseModel
{
    protected $hidden = ['update_time','delete_time'];
    public function items(){
        //第一个参数，关联模型名，第二个参数 外键(Banner表和Banner_item表关联的外键)，第三个参数 传入当前模型(Banner)的主键)，
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function getBannerById($id) {
        //with 传参也可以是数组 with(['items','items1'])
        //items.img items下面还要带有img  嵌套的关联关系
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }
}
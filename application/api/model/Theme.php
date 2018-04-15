<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/9
 * Time: 10:18
 */
namespace app\api\model;
class Theme extends BaseModel
{
    protected $hidden = ['delete_time','update_time','topic_img_id','head_img_id'];
    public function topicImg(){
        //一对一关联 Theme 和 Image 一对一关联,一对一也是有主从关系的
        //查询的Theme 通过Theme 获取Image，所以在这儿定义关联关系
        // $this->hasOne()
        //belongsTo 一对一是不对等的
        //如果一个表或者是一个模型里它本身是包含一个外键的，那么就把它定义成belongsTo.反过来，如果一个模型里面没有这个外键，外键是存在于它相关联的另外一个表里面的话呢,就定义成hasOne,
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }
    //多对多关联
    public function products(){
        //   各个 参数      当前模型名，中间表名，中间表对应外键
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }
    public static function getThemeByIDs($ids){
        $theme = self::with(['topicImg','headImg'])->select($ids);
        return $theme;
    }

    public static function getThemeWithProducts($id){
        $theme = self::with('products,topicImg,headImg')
            ->select($id);
        return $theme;
    }
}
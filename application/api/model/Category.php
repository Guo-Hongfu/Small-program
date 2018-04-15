<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/10
 * Time: 17:30
 */
namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden = [
      'delete_time','update_time','create_time'
    ];
    //关联img表，一对一
    public function Img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}
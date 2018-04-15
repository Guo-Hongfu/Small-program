<?php
namespace app\api\model;
use think\Model;
class BaseModel extends Model
{
    //模型读取器， get 和 Attr 是固定值。
    protected function prefixImgUrl($value, $data) {
        $finalUrl = $value;
        if ($data['from'] == 1) {
            //from 等于 1 的时候 ，就说明图片存在服务器本身上，拼接image的url地址。否则就不拼接、
            $finalUrl = config('setting.img_prefix') . $value;
        }
        return $finalUrl;
    }
}

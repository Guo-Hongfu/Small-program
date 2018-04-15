<?php
namespace app\api\controller\v2;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 11:24
 */
use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;


class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     * @param
     * @return string
     * @throws BannerMissException
     */
    public function getBanner($id){
        return 'This is V2 Version';
    }
}
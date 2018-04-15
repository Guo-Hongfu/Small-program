<?php
namespace app\api\controller\v1;
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
     * http://www.tp5.com/api/v1/banner/1
     * @http GET
     * @id banner的id号
     * @param
     * @return string
     * @throws BannerMissException
     */
    public function getBanner($id){
        //开闭原则，

        //AOP面向切面编程
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerById($id);
        if($banner->isEmpty()){
            throw new BannerMissException();
        }
        return $banner; //config里默认输出类型 'html -> json'
    }
}
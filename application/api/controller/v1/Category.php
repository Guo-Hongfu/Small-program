<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/10
 * Time: 17:29
 */
namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    /**
     * @return false|static[]
     * @throws CategoryException
     * http://www.tp5.com/api/v1/category/all
     * GET
     */
    public function getAllCategories(){
        //和 CategoryModel::with('img')->select() 得到的结果一样
        $categories = CategoryModel::all([],'img');
        if ($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}
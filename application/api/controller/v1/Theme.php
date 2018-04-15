<?php
namespace app\api\controller\v1;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;
use think\Controller;
use app\api\model\Theme as ThemeModel;
class Theme extends Controller
{
    /**
     * @url /theme?ids=id1,id2,id3...
     * @param string $ids
     * @return 一组theme模型
     * @throws ThemeException
     * GET
     * http://www.tp5.com/api/v1/theme?ids=1,2
     */
    public function getSimpleList($ids=''){
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);
        $result = ThemeModel::getThemeByIDs($ids);
        if ($result->isEmpty()){
            throw new ThemeException();
        }
        return $result;
    }

    /**
     * @param $id
     * @return string
     * @throws ThemeException
     * @url / theme/:id
     * http://www.tp5.com/api/v1/theme/1
     * GET
     */
    public function getComplexOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if ($theme->isEmpty()){
            throw new ThemeException();
        }
        return $theme;
    }
}

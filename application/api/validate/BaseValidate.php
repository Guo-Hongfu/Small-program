<?php
namespace app\api\validate;

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/24
 * Time: 14:11
 * 公用验证器goCheck()方法
 */
use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;


class BaseValidate extends Validate
{
    public function goCheck($scene = '')
    {
        //获取http传入的参数
        //对这些参数进行校验
        $params = Request::instance()->param();
        $result = $this->scene($scene)->batch()->check($params);
        if (!$result) {
            $e = new ParameterException([
                'msg' => $this->error,
//                'code' => 400,
//                'errorCode' => 10002
            ]);
//            $e->msg = $this->error;
            throw $e;
//            $error = $this->error;
//            throw new Exception($error);
        } else {
            return true;
        }
    }

    /**
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * 验证参数id是否是正整数
     * @return bool
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }
    protected function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if ($result){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 判断不为空
     */
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }

    //根据验证器的规则获取指定的数据
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) |
            array_key_exists('uid', $arrays)
        ) {
            //不允许包含user_id或者uid,防止恶意覆盖user_id或者uid
            throw new ParameterException(
                [
                    'msg' => '参数中包含非法的参数名user_id或者uid',
                ]);
        }
        $newArray = [];
        // $this->rule 定义的规则数组
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

}


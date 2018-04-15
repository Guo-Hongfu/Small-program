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
    public function goCheck($scene='') {
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
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '') {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断不为空
     */
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '') {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }
}


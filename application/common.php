<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]

// 应用公共文件
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验 为 false，部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * 生成一组随机字符串,由length决定长度
 * @param $length
 * @return null|string
 */
function getRandChars($length)
{
    $str = null;
    $strPro = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghjklmnopqrstuvwxyz';
    $max = strlen($strPro) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPro[rand(0, $max)];
    }
    return $str;
}


//(A,B) C (B,C)
function zuhe($a, $b, $c)
{
    $aArr = explode(',', $a);
    $cArr = explode(',', $c);
    $newA = $newAC =$newC=[];
    foreach ($aArr as $item) {
        $newA[] = $item . $b;
    }
    foreach ($cArr as $item) {
        $newC[] = $item . $b;
    }
    foreach ($newA as $value) {
        foreach ($cArr as $key) {
            $newAC[] = $value.$key ;
        }
    }

    return array_merge($newA,$newC,$newAC);
}
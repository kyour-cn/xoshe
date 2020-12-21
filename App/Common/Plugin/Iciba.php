<?php
/**
 * 金山词霸相关
 * Class Iciba
 */
namespace App\Common\Plugin;

class Iciba
{
    static public function oneDayOneSentence()
    {

        if($body = cache('iciba_oneDayOneSentence') and $body['ymd'] == date('Ymd')){
            //使用缓存
        }else{
            $body_str = file_get_contents('http://open.iciba.com/dsapi/');
            $body = json_decode($body_str,true);
            $body['ymd'] = date('Ymd');

            //计算缓存时间并保存
            $cache_time = (strtotime(date( "Y-m-d")) + 86400) - time();
            cache('iciba_oneDayOneSentence', $body, $cache_time);
        }
        $body['time'] = time();

        return $body;
    }

}
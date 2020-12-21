<?php
/**
 * 常用工具类
 * @author kyour@vip.qq.com
 */
namespace App\Common;

use App\Common\Extend\Oss;
use App\Common\Extend\ResApi;
use EasySwoole\RedisPool\Redis;
use FtpClient\FtpClient;

class Utils
{
    /**
     * 获取redis实例
     * @return \EasySwoole\Redis\Redis|null
     */
    public static function getRedis()
    {
        return Redis::defer('redis');
    }

    /**
     * 资源文件保存
     * @param string $file 临时文件路径
     * @param string $target 目标路径
     * @param string|null $type 储存类型 local|oss
     * @return array
     */
    public static function saveFile(string $file, string $target, $type = null)
    {
        if($type === null){
            $type = config('app.res_type');
        }

        switch ($type) {
            case 'local': //本地储存
                $index = strrpos($target, '/');

                //保存到Public目录下
                $dir = EASYSWOOLE_ROOT. '/Public/'.substr($target, 0, $index);
                //文件夹创建
                if (!file_exists($dir)){
                    mkdir($dir,0777,true);
                }
                if(copy($file, $dir.'/'.substr($target, $index +1))){
                    return [null, true];
                }
                return ['fail', false];
            case 'oss': //阿里对象储存
                $oss = new Oss();
                return $oss->upload($file, $target);
//            case 'ftp': //FTP服务器
//                $ftp = new FtpClient();
//                return $ftp->upload($file, $target);
            case 'api': //文件服务器接口
                $oss = new ResApi();
                return $oss->upload($file, $target);
            default:
                break;
        }
        return ['fail', false];
    }

    /**
     * 资源文件删除
     * @param string $target 文件路径
     * @param string|null $type 储存类型 local|oss
     * @return array
     */
    public static function delFile(string $target, $type = null)
    {
        if($type === null){
            $type = config('app.res_type');
        }
        switch ($type) {
            case 'local': //本地储存
                $path = EASYSWOOLE_ROOT. '/Public/' . $target;
                if(@unlink($path)){
                    return [null, true];
                }
                return ['fail', false];
            case 'oss': //阿里对象储存
                $oss = new Oss();
                return $oss->delete($target);
            case 'api': //文件服务器接口
                $oss = new ResApi();
                return $oss->delete($target);
            default:
                break;
        }
        return ['fail', false];
    }

    /**
     * 将Base64图片转换为本地图片并保存
     * @param $base64_image string 图片Base64字符串
     * @param $path string 储存文件路径 为''则直接返回
     * @return bool|string
     */
    public static function base64Image(string $base64_image, string $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            $raw = base64_decode(str_replace($result[1], '', $base64_image));
            if($path === ''){
                return $raw;
            }
            return (bool)file_put_contents($path, $raw);
        }else{
            return false;
        }
    }

    /**
     * 将本地图片转换为Base64图片并返回
     * @param $file string 文件路径
     * @return string
     */
    public static function imageBase64(string $file):string
    {
        $base64_file = '';
        if(file_exists($file)){
            $mime_type= mime_content_type($file);
            $base64_data = base64_encode(file_get_contents($file));
            $base64_file = 'data:'.$mime_type.';base64,'.$base64_data;
        }
        return $base64_file;
    }

    /**
     * emoji特殊字符转义-编码
     * @param string $str string
     * @return string
     */
    public static function emojiTextEncode(string $str): string
    {
        $text = json_encode($str); //暴露出unicode
        //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        $text = preg_replace_callback("/(\\\u[2def][0-9a-f]{3})/i",function($str){
            return addslashes($str[0]);
        },$text);
        return json_decode($text);
    }

    /**
     * emoji特殊字符转义-解码
     * @param $str string
     * @return string
     */
    public static function emojiTextDecode(string $str): string
    {
        $text = json_encode($str); //暴露出unicode
        //将两条斜杠变成一条，其他不动
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text);
        return json_decode($text);
    }

    /**
     * 将某个时间到某个时间戳差值转换为文字描述
     * 如：刚刚、5分钟前、3天前... 超过一年显示日期
     * @param int $time 开始时间
     * @param int $now 结束时间|现在
     * @return string
     */
    public static function formatExperience(int $time, int $now = 0): string
    {
        $now == 0 and $now = time();
        $diff = $now - $time;
        switch (true) {
            case ($diff < 60):
                $tip = '刚刚';
                break;
            case ($diff < 3600):
                $tip = intval($diff / 60).'分钟前';
                break;
            case ($diff <  86400):
                $tip = intval($diff / 3600).'小时前';
                break;
            case ($diff <  86400 * 30):
                $tip = intval($diff / 86400).'天前';
                break;
            default:
                $tip = date('y.m.d H:i', $time);
                break;
        }
        return $tip;
    }

}

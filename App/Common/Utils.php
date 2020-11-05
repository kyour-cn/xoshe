<?php
namespace App\Common;

class Utils
{
    //获取redis连接
    public static function getRedis()
    {
        return \EasySwoole\RedisPool\Redis::defer('redis');
    }

    //文件保存
    public static function saveFile(string $file, string $target, $type = 'local')
    {
        switch ($type) {
            case 'local': //本地储存
                $index = strrpos($target, '/');
                $path = substr($target, 0, $index);
                $fname = substr($target, $index +1);

                //保存到Public目录下
                $dir = EASYSWOOLE_ROOT. '/Public/'.$path;
                //文件夹创建
                if (!file_exists($dir)){
                    mkdir($dir,0777,true);
                }
                if(copy($file, $dir.'/'.$fname)){
                    return [null, true];
                }
                return ['fail', false];
                break;
            case 'oss': //阿里对象储存
                $oss = new \App\Common\Extend\Oss();
                return $oss->upload($file, $target);
                break;
            default:
                break;
        }
    }

    //emoji特殊字符转义-编码
    public static function emojiTextEncode(string $str)
    {
        $text = json_encode($str); //暴露出unicode
        //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        $text = preg_replace_callback("/(\\\u[2def][0-9a-f]{3})/i",function($str){
            return addslashes($str[0]);
        },$text);
        return json_decode($text);
    }

    //emoji特殊字符转义-解码
    public static function emojiTextDecode($str){
        $text = json_encode($str); //暴露出unicode
        //将两条斜杠变成一条，其他不动
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text);
        return json_decode($text);
    }
}
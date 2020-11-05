<?php
namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\Crontab;
/**
 * 定时任务注册
 * 
 */

class Rergister
{
    //需要注册定时任务的类名
    static $className = [
        TaskMinute::class,
        
    ];

    public static function run()
    {
        //批量注册类
        foreach (self::$className as $c) {
            Crontab::getInstance()->addTask($c);
        }

        //其他定时器

    }
}

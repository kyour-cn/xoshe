<?php
namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\Component\Process\AbstractProcess;
/**
 * 定时任务注册
 * 
 */

class Rergister extends AbstractProcess
{
    //需要注册定时任务的类名
    static $className = [
        // TaskMinute::class,
        
    ];

    protected function run($arg)
    {
        //批量注册类
        foreach (self::$className as $c) {
            Crontab::getInstance()->addTask($c);
        }

        //其他定时器

        // 每隔 10 秒执行一次
        // \EasySwoole\Component\Timer::getInstance()->loop(5 * 1000, function () {
            // echo "this timer runs at intervals of 10 seconds\n";
        // });
    }
}

<?php
namespace App\Crontab;

/**
 * 每分钟一次
 */
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;
use EasySwoole\EasySwoole\Task\TaskManager;

class TaskMinute extends AbstractCronTask
{

    public static function getRule(): string
    {
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        return  __CLASS__;
    }

    function run(int $taskId, int $workerIndex)
    {
        // echo __CLASS__ . "\n";
        
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        echo $throwable->getMessage();
    }
}
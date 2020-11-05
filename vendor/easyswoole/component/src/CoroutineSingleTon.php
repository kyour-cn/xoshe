<?php


namespace EasySwoole\Component;


use Swoole\Coroutine;

trait CoroutineSingleTon
{
    private static $instance = [];

    static function getInstance(...$args)
    {
        $cid = Coroutine::getCid();
        if(!isset(self::$instance[$cid])){
            self::$instance[$cid] = new static(...$args);
            /*
             * 兼容非携程环境
             */
            if($cid > 0){
                Coroutine::defer(function ()use($cid){
                    unset(self::$instance[$cid]);
                });
            }
        }
        return self::$instance[$cid];
    }

    function destroy(int $cid = null)
    {
        if($cid === null){
            $cid = Coroutine::getCid();
        }
        unset(self::$instance[$cid]);
    }
}
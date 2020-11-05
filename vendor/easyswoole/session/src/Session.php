<?php


namespace EasySwoole\Session;

use EasySwoole\Session\Exception\Exception;
use EasySwoole\Spl\SplContextArray;
use EasySwoole\Utility\Random;
use Swoole\Coroutine;

class Session
{
    private static $instance;
    /** @var \SessionHandlerInterface */
    private $handler;
    //默认活跃时间为一星期
    private $maxLifeTime = 3600*24*7;
    private $sessionDataContext;
    private $sessionConfigContext;
    private $autoClear = true;
    private $savePath;
    private $name;
    private $gc_cycle_times = 50000;
    private $callTimes = 0;
    private $onClose;
    private $onStart;

    function __construct(\SessionHandlerInterface $storage,$sessionName = 'easy_session',$savePath = '/')
    {
        $this->name = $sessionName;
        $this->savePath = $savePath;
        $this->handler = $storage;
        $this->sessionDataContext = new SplContextArray(false);
        $this->sessionConfigContext = new SplContextArray(false);
    }

    public static function getInstance(...$args)
    {
        if(!isset(self::$instance)){
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }

    function setOnClose(callable $call)
    {
        $this->onClose = $call;
        return $this;
    }

    function setOnStart(callable $call)
    {
        $this->onStart = $call;
        return $this;
    }

    function sessionId(string $sid = null):string
    {
        if($sid){
            if(!$this->sessionConfigContext['isStart']){
                $this->sessionConfigContext['sid'] = $sid;
            }else{
                throw new Exception('can not modify sid after session start');
            }
        }else{
            if(empty($this->sessionConfigContext['sid'])){
                $this->sessionConfigContext['sid'] = strtolower(Random::character(32));
            }
        }
        return $this->sessionConfigContext['sid'];
    }

    function set($key,$data)
    {
        $this->start();
        $this->sessionDataContext[$key] = $data;
    }

    function get($key)
    {
        $this->start();
        return $this->sessionDataContext[$key];
    }

    function del($key)
    {
        $this->start();
        unset($this->sessionDataContext[$key]);
    }

    function all():?array
    {
        $this->start();
        return $this->sessionDataContext->toArray();
    }

    function destroy()
    {
        $this->start();
        try{
            $sid = $this->sessionId();
            $this->handler->destroy($sid);
            $this->sessionConfigContext['destroy'] = true;
            $this->writeClose();
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            $this->writeClose();
        }
    }

    function writeClose()
    {
        try{
            if($this->sessionConfigContext['isStart']){
                if($this->onClose){
                    call_user_func($this->onClose,$this);
                }
                if(!$this->sessionConfigContext['destroy']){
                    $data = $this->sessionDataContext->toArray();
                    $this->handler->write($this->sessionId(),serialize($data));
                }
                $this->handler->close();
            }
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            //清理空间
            $this->sessionConfigContext->destroy();
            $this->sessionDataContext->destroy();
        }
    }

    function start()
    {
        if(!$this->sessionConfigContext['isStart']){

            //gc准确计数
            $this->callTimes++;
            if($this->gc_cycle_times && $this->callTimes > $this->gc_cycle_times){
                $this->callTimes = 0;
                Coroutine::create(function (){
                    $this->gc();
                });
            }
            try{
                $ret = $this->handler->open($this->savePath,$this->name);
                if(!$ret){
                    throw new Exception("session handler open savePath {$this->savePath} for saveName {$this->name} fail");
                }
                $data = unserialize($this->handler->read($this->sessionId()));
                if(is_array($data)){
                    foreach ($data as $key => $val){
                        $this->sessionDataContext[$key] = $val;
                    }
                }
                $this->sessionConfigContext['isStart'] = true;
                if($this->onStart){
                    call_user_func($this->onStart,$this);
                }
            }catch (\Throwable $throwable){
                //防止context内存泄漏
                $this->writeClose();
                throw $throwable;
            }
            if($this->autoClear){
                Coroutine::defer(function (){
                    $this->writeClose();
                });
            }
        }
        return $this->sessionConfigContext['isStart'];
    }

    function gc():Session
    {
        $this->start();
        $this->handler->gc($this->maxLifeTime);
        return $this;
    }

    function setMaxLiftTime(int $ttl):Session
    {
        $this->maxLifeTime = $ttl;
        return $this;
    }

    function getContextArray():SplContextArray
    {
        return $this->sessionDataContext;
    }

    function setAutoClear(bool $ret)
    {
        $this->autoClear = $ret;
    }
}

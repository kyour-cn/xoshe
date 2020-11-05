<?php


namespace EasySwoole\Session;


use EasySwoole\Component\ChannelLock;
use EasySwoole\Spl\SplContextArray;
use EasySwoole\Spl\SplFileStream;

class SessionFileHandler implements \SessionHandlerInterface
{
    private $temp;
    private $contextArray;

    function __construct(?string $tempDir = null)
    {
        ChannelLock::getInstance();
        if(!$tempDir){
            $tempDir = sys_get_temp_dir();
        }
        $this->temp = $tempDir;
        $this->contextArray = new SplContextArray(false);
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $stream = $this->getStream($session_id);
        ChannelLock::getInstance()->lock($session_id);
        $stream->lock();
        try{
            $file = "{$this->contextArray['path']}/{$session_id}";
            unlink($file);
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            $stream->unlock();
            $stream->close();
            ChannelLock::getInstance()->unlock($session_id);
            unset($this->contextArray['path']);
            unset($this->contextArray['stream']);
        }
        return true;
    }

    public function gc($maxlifetime)
    {
        //空实现
    }

    public function open($save_path, $name)
    {
        $save_path = trim($save_path,'/');
        $dir = $this->temp.'/'.$save_path;
        $this->contextArray['path'] = $dir;
        if(!is_dir($dir)){
            return mkdir($this->temp.'/'.$save_path,0777,true);
        }
        return true;
    }

    public function read($session_id)
    {
        $stream = $this->getStream($session_id);
        //防止请求落同进程
        ChannelLock::getInstance()->lock($session_id);
        $stream->lock();
        return $stream->__toString();
    }

    public function write($session_id, $session_data)
    {
        $stream = $this->getStream($session_id);
        ChannelLock::getInstance()->lock($session_id);
        $stream->lock();
        try{
            $stream->truncate();
            $stream->seek(0);
            $stream->write($session_data);
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            $stream->unlock();
            $stream->close();
            ChannelLock::getInstance()->unlock($session_id);
            unset($this->contextArray['path']);
            unset($this->contextArray['stream']);
        }
    }

    private function getStream(string $session_id)
    {
        $file = "{$this->contextArray['path']}/{$session_id}";
        if(!$this->contextArray['stream']){
            $stream = new SplFileStream($file,'c+');
            $this->contextArray['stream'] = $stream;
        }else{
            $stream = $this->contextArray['stream'];
        }
        return $stream;
    }
}
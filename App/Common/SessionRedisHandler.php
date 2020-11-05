<?php

namespace App\Common;

/**
 * 实现Redis Session
 * @authar kyour.cn
 */

class SessionRedisHandler implements \SessionHandlerInterface
{
    private $redis;
    private $redisPool;
    private $expire = 86400 * 7; //7天

    /**
     * SESSION关闭
     * @return  boolean
     */
    public function close()
    {
        $this->redisPool->recycleObj($this->redis);

        return true;
    }

    /**
     * SESSION打开
     * @param   save_path   string 保存路径
     * @param   session_name string 会话id
     * @return  boolean 是否成功
     */
    public function open($save_path, $name)
    {

        $this->redisPool = \EasySwoole\RedisPool\Redis::getInstance()->get('redis');
        $this->redis= $this->redisPool->getObj();
        return true;
    }

    /**
     * 回收超时SESSION信息
     * @param
     * @return boolean
     */
    public function gc($maxlifetime)
    {
        //空实现
    }

    /**
     * 写入SESSION信息
     * @param   key string session的key值
     * @param   val string session数值
     * @return  boolean
     */
    public function write($session_id, $session_data)
    {
        // $this->redis = \EasySwoole\RedisPool\Redis::defer('redis');
        $this->redis->set($session_id, $session_data, $this->expire);
    }

    /**
     * 删除Session信息
     * @param   key string Session的key值
     * @return  boolean
     */
    public function destroy($session_id)
    {
        // $this->redis = \EasySwoole\RedisPool\Redis::defer('redis');
        $this->redis->del($session_id);
        return true;
    }

    /**
     * 读取SESSION信息并验证是否有效
     * @param   key string session的key值
     * @return  mixed
     */
    public function read($session_id)
    {
        // $this->redis = \EasySwoole\RedisPool\Redis::defer('redis');
        return $this->redis->get($session_id);
    }

}
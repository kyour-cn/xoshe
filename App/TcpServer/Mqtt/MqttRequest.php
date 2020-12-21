<?php


namespace App\TcpServer\Mqtt;


use Simps\MQTT\Packet\Pack;

class MqttRequest
{
    public $data;
    public $header;
    public $fd;
    public $server;

    protected $appName = '';

    /**
     * MqttRequest constructor.
     */
    public function __construct()
    {
        $this->appName = config('app.app_name');
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server): void
    {
        $this->server = $server;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @param int $fd
     */
    public function setFd(int $fd): void
    {
        $this->fd = $fd;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header): void
    {
        $this->header = $header;
    }

    /**
     * Session操作
     * @param string $key
     * @param string $data
     * @param null $expire
     * @return mixed|null
     * @throws \EasySwoole\Redis\Exception\RedisException
     */
    public function session(string $key = SWORD_NULL, $data = SWORD_NULL, $expire = null)
    {
        //取出设备fd
        $mac = $this->sessBind();
        //判断设备是否有绑定这个
        if(!$mac) return null;

        $hash = "{$this->appName}.dev.sess_{$mac}";
        $value = cache($hash);

        if($key == SWORD_NULL){
            return $value;
        }elseif($data == SWORD_NULL){
            return $value[$key] ?? null;
        }elseif($data == null){
            unset($value[$key]);
            cache($hash, $value);
        }else{
            $value[$key] = $data;
            cache($hash, $value);
        }
        return null;
    }

    /**
     * 缓存-将fd绑定到mac标识的session
     * @param string|null $mac
     * @return mixed|null
     * @throws \EasySwoole\Redis\Exception\RedisException
     */
    public function sessBind(string $mac = null)
    {
        $hash = "swc.{$this->appName}.bindfd_".$this->getFd();
        if($mac){
            //设定绑定mac
            cache($hash, $mac);
        }else{
            //返回绑定mac
            $mac = cache($hash);
            return $mac;
        }
    }

    /**
     * 返回消息
     * @param string $topic
     * @param $message
     */
    public function send(string $topic, $message)
    {
        $this->sendTo($this->fd, $topic, $message);
    }

    /**
     * 发送到指定fd的消息
     * @param int $fd
     * @param string $topic
     * @param $message
     */
    public function sendTo(int $fd, string $topic, $message)
    {
        if(is_array($message)){
            $message = json_encode($message);
        }

        $data = Pack::publish([
            'topic' => $topic,
            'message' => $message
        ]);

        $this->server->send($fd, $data);
    }
}

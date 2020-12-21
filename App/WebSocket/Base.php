<?php declare(strict_types=1);
namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\Controller;
use EasySwoole\Session\Session;

/**
 * Class BaseSocket
 *
 * 此类是websocket控制器基类
 *
 * @package App\WebSocket
 */
class Base
{
    public $args;
    public $response;

    public function __construct($args, $response)
    {
        $this->args = $args;
        $this->response = $response;
        // parent::__construct()
    }

    //Session操作
    public function session(string $key = SWORD_NULL, $data = SWORD_NULL, $expire = null)
    {
        // $args = $this->caller()->getArgs();
        $args = $this->args;

        $sname = config('session.sessionName');
        if(empty($args[$sname])){
            return false;
        }

        $value = cache($args[$sname]);

        if($key == SWORD_NULL){
            return $value;
        }elseif($data == SWORD_NULL){
            return $value[$key] ?? null;
        }elseif($data == null){
            unset($value[$key]);
            cache($args[$sname], $value);
        }else{
            $value[$key] = $data;
            cache($args[$sname], $value);
        }
    }

    /**
     * 获取sessionId
     * @return bool|string
     */
    public function sessionId()
    {
        $args = $this->args;

        $sname = config('session.sessionName');
        if(empty($args[$sname])){
            return false;
        }
        return $args[$sname];
    }

    /**
     * api接口返回数据，封装统一规则
     * @param int $code 错误代码，0为无错误
     * @param string $msg 响应提示文本
     * @param array|object $result 响应数据主体
     * @param int $count 统计数量，用于列表分页
     * @return bool
     */
    public function withData(int $code = 0, string $msg = '', $result = [], int $count = -1): bool
    {
        $ret = [
            'status' => $code?0:1,
            'code'   => $code,
            'result'   => $result,
            'message'=> $msg
        ];

        if($count >= 0) $ret['count'] = $count;

        //判断是否存在ajax的token
        $args = $this->args;
        if(!empty($args['ES_TOKEN'])){
            $ret['ES_TOKEN'] = $args['ES_TOKEN'];
        }
        // echo json_encode($ret, JSON_UNESCAPED_UNICODE)."\n";
        $this->response->setMessage(json_encode($ret));

        return true;
    }

}

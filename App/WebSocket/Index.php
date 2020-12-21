<?php
namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\Controller;

use EasySwoole\Session\Session;

use App\WebSocket\Base;

use App\Common\Utils;
use App\Common\Db;

use App\Model\User as UserModel;

/**
 * Class Index
 *
 * 此类是默认的 websocket 消息解析后访问的 控制器
 *
 * @package App\WebSocket
 */
class Index extends Controller
{

    //心跳处理
    public function heart()
    {
        $param = $this->caller()->getArgs();
        $base = new Base($param, $this->response());

        $s_user = $base->session('user');
        //判断是否在别处登录
        if($s_user){
            $token = UserModel::create()->where('id', $s_user['id'])->val('token');
            if($token != $base->sessionId()){
                //清空session
                $base->session('user', null);
            }
        }
    }

//    public function ajax()
//    {
//        $param = $this->caller()->getArgs();
//        $base = new Base($param, $this->response());
//
//        $s_user = $base->session('user');
//        if(!$s_user){
//            $base->withData(1, '未登录');
//        }else{
//            $base->withData(0, '已登录', $s_user);
//        }
//    }
}

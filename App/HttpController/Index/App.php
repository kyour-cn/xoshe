<?php
namespace App\HttpController\Index;

// use App\Common\Utils;

use EasySwoole\Session\Session;

class App extends BaseAuth
{

    public function check()
    {
        //目前用于session创建
        
    }

    //获取sessionId
    public function getSessionId()
    {
        $sid = Session::getInstance()->sessionId();

        $this->withData(0, 'success', [
            'sid' => $sid
        ]);
    }

}
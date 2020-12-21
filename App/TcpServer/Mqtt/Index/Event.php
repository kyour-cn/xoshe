<?php


namespace App\TcpServer\Mqtt\Index;


use App\TcpServer\Mqtt\MqttController;
use App\TcpServer\Mqtt\MqttRequest;

class Event extends MqttController
{

    public function heart(MqttRequest $req)
    {
//        $req->session('time', time());
    }

}
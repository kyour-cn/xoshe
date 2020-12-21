<?php


namespace App\TcpServer\Mqtt\Index;


use App\TcpServer\Mqtt\MqttController;
use App\TcpServer\Mqtt\MqttRequest;

use App\Model\Device as DeviceModel;

class Device extends MqttController
{
    //注册设备上线
    public function regist(MqttRequest $req)
    {
        $data = $req->getData();

        //绑定session
        $req->sessBind($data['mac']);

        $model = DeviceModel::create()->get(['identifier' => $data['mac']]);
        if(!$model){
            $model = DeviceModel::create([
                'identifier' => $data['mac'],
                'online' => 1,
                'extend' => '{}',
                'data' => '{}',
            ]);
            $model->save();
        }

        $model->update(['online' => 1]);

        //更新session
        $req->session('info', $model);

        $req->send('regist_notice', [
            'time' => time()
        ]);

    }

}
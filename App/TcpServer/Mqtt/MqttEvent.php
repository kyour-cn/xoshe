<?php


namespace App\TcpServer\Mqtt;


class MqttEvent
{

    //事件处理方法
    public static function connect(\Swoole\Server $server, int $fd, int $reactor_id) {
//        echo "fd:{$fd} 已连接\n";
    }

    //事件处理方法
    public static function receive(\Swoole\Server $server, int $fd, int $reactor_id, string $data)
    {
        $header = MqttEvent::mqttGetHeader($data);

        if ($header['type'] == 1) {
            $resp = chr(32) . chr(2) . chr(0) . chr(0);
            MqttEvent::eventConnect($header, substr($data, 2));
            $server->send($fd, $resp);
            echo "新MQTT设备连接成功:$fd \n";
            return;
        } elseif ($header['type'] == 3) {

            $offset = 2;
            $topic = MqttEvent::decodeString(substr($data, $offset));
            $offset += strlen($topic) + 2;
            $msg = substr($data, $offset);

            // 控制器实现
            $cmd = explode('.', $topic);
            $ns_len = count($cmd) -1;

            $ns_str = '';
            for($i = 0; $i < $ns_len; $i++){
                $ns_str .= '\\'.ucfirst($cmd[$i]);
            }

            $class = '\\App\\TcpServer\\Mqtt'. ($ns_str ?? 'Index');

            if(!class_exists($class)){
                echo "cmd class $class does not exist. \n";
                return null;
            }

            try {
                //解析json对象
                if($json_data = json_decode($msg, true) and !is_null($json_data)){
                    $msg = $json_data;
                }

                //移交控制器处理
                $action = $cmd[$ns_len];
                $req = new MqttRequest();
                $req->setHeader($header);
                $req->setData($msg);
                $req->setFd($fd);
                $req->setServer($server);
                (new $class)->$action($req);

            }catch (\Throwable $e){
                echo $e->getMessage();
            }
            return;
        } elseif ($header['type'] == 12) {
            //心跳
            $class = '\\App\\TcpServer\\Mqtt\\Index\\Event';
            //移交控制器处理
            $action = 'heart';
            $req = new MqttRequest();
            $req->setHeader($header);
            $req->setFd($fd);
            $req->setServer($server);
            (new $class)->$action($req);
            return;
        }
        echo "received type={$header['type']} length=" . strlen($data) . "\n";

    }

    //事件处理方法
    public static function close(\Swoole\Server $server, int $fd, int $reactor_id)
    {
        echo "fd:{$fd} 已关闭\n";
    }

    public static function decodeValue($data)
    {
        return 256 * ord($data[0]) + ord($data[1]);
    }

    public static function decodeString($data)
    {
        $length = self::decodeValue($data);
        return substr($data, 2, $length);
    }

    public static function mqttGetHeader($data)
    {
        $byte = ord($data[0]);

        $header['type'] = ($byte & 0xF0) >> 4;
        $header['dup'] = ($byte & 0x08) >> 3;
        $header['qos'] = ($byte & 0x06) >> 1;
        $header['retain'] = $byte & 0x01;

        return $header;
    }

    public static function eventConnect($header, $data)
    {
        $connect_info['protocol_name'] = self::decodeString($data);
        $offset = strlen($connect_info['protocol_name']) + 2;

        $connect_info['version'] = ord(substr($data, $offset, 1));
        $offset += 1;

        $byte = ord($data[$offset]);
        $connect_info['willRetain'] = ($byte & 0x20 == 0x20);
        $connect_info['willQos'] =    ($byte & 0x18 >> 3);
        $connect_info['willFlag'] =   ($byte & 0x04 == 0x04);
        $connect_info['cleanStart'] = ($byte & 0x02 == 0x02);
        $offset += 1;

        $connect_info['keepalive'] = self::decodeValue(substr($data, $offset, 2));
        $offset += 2;
        $connect_info['clientId'] = self::decodeString(substr($data, $offset));
        return $connect_info;
    }

}
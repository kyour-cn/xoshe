<?php
namespace EasySwoole\EasySwoole;

use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

use EasySwoole\RedisPool\Redis;
use EasySwoole\Socket\Dispatcher;
use App\WebSocket\WebSocketParser;

use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\Db\Config as DbConfig;
use EasySwoole\Redis\Config\RedisConfig;

use EasySwoole\Template\Render;
use App\Common\TemplateRender;
use App\Common\ExceptionHandler;

use EasySwoole\Session\Session;
use EasySwoole\Session\SessionFileHandler;
use App\Common\SessionRedisHandler;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\Manager;

class EasySwooleEvent implements Event
{

    //初始化
    public static function initialize()
    {
        // TODO: Implement initialize() method.

        // 载入助手函数
        include_once EASYSWOOLE_ROOT . '/App/helper.php';

        //时区设置
        date_default_timezone_set(config('app.timezone')?:'Asia/Shanghai');

        // -------------------- DB --------------------
        //创建数据库连接池
        $db_conf = config('db');
        $config = new DbConfig();
        //数据库配置
        $config->setDatabase($db_conf['name'])
            ->setUser($db_conf['user'])
            ->setPassword($db_conf['password'])
            ->setHost($db_conf['host'])
            ->setPort($db_conf['port'])
            ->setCharset('utf8mb4')

            //连接池配置
            ->setGetObjectTimeout(3.0) //设置获取连接池对象超时时间
            ->setIntervalCheckTime(30*1000) //设置检测连接存活执行回收和创建的周期
            ->setMaxIdleTime(15) //连接池对象最大闲置时间(秒)
            ->setMaxObjectNum(50) //设置最大连接池存在连接对象数量
            ->setMinObjectNum(10) //设置最小连接池存在连接对象数量
            ->setAutoPing(5); //设置自动ping客户端链接的间隔

        DbManager::getInstance()->addConnection(new Connection($config));
        unset($db_conf, $config);
        // -------------------- DB END --------------------

        // -------------------- REDIS --------------------
        //redis连接池注册
        $r_conf = config('redis');
        Redis::getInstance()
            ->register('redis', new RedisConfig([
                'host'      => $r_conf['host'],
                'port'      => $r_conf['port'],
                'auth'      => $r_conf['password'],
                'serialize' => RedisConfig::SERIALIZE_NONE,
                'db'        => $r_conf['db']
            ]));
        unset($r_conf);
        // -------------------- REDIS END --------------------

        // -------------------- HTTP --------------------
        //onRequest事件
        Di::getInstance()->set(SysConst::HTTP_GLOBAL_ON_REQUEST,function (Request $request,Response $response){

            //启用Session会话
            $session_conf = config('session');
            $cookie = $request->getCookieParams($session_conf['sessionName']);
            if(empty($cookie)){
                $sid = Session::getInstance()->sessionId();
                $response->setCookie($session_conf['sessionName'], $sid, time() + $session_conf['expire']);
            }else{
                Session::getInstance()->sessionId($cookie);
            }
            return true;
        });

        // 注册异常处理
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER,[ExceptionHandler::class,'handle']);
        // -------------------- HTTP END --------------------

    }

    //服务创建
    public static function mainServerCreate(EventRegister $register)
    {
        /**
         * **************** Redis 缓存清理 **********************
         */
        (function(){
            $r_conf = config('redis');

            //删除wsc开头的临时缓存
            $redis = new \Redis();
            $redis->connect($r_conf['host'], $r_conf['port']);
            if(!empty($r_conf['password']))
                $redis->auth($r_conf['password']); //密码验证

            $iterator = null;
            $i = 0;//计数
            while (true) {
                $keys = $redis->scan($iterator, 'swc_*');
                if ($keys === false) {
                    //迭代结束，未找到匹配pattern的key
                    echo "del caches ok,count:$i\n";
                    $redis->close();
                    return; //退出
                }
                foreach ($keys as $key) {
                    $i++;
                    $redis->del($key);
                }
            }
        })();

        /**
         * **************** websocket控制器 **********************
         */
        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();
        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);
        // 给server 注册相关事件 在 WebSocket 模式下  on message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage, function (\Swoole\Server $server, \Swoole\WebSocket\Frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });

        /**
         * **************** 模板引擎 **********************
         * -在全局的主服务中创建事件中，实例化该Render,并注入你的驱动配置
         */
        Render::getInstance()->getConfig()->setRender(new TemplateRender());
        Render::getInstance()->getConfig()->setTempDir(EASYSWOOLE_TEMP_DIR);
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());

        /**
         * **************** 启动Session服务 **********************
         */
        $session_conf = config('session');
        //Session -可以自己实现一个标准的session handler
        if($session_conf['type'] == 'redis'){
            $handler = new SessionRedisHandler();
        }elseif($session_conf['type'] == 'file'){
            $handler = new SessionFileHandler(EASYSWOOLE_TEMP_DIR);
        }
        //表示cookie name   还有save path
        Session::getInstance($handler, $session_conf['sessionName'], 'session_dir');

        /**
         * **************** Crontab任务计划 **********************
         */
        Manager::getInstance()->addProcess(new \App\Crontab\Rergister());

        /**
         * **************** 启动其他TcpServer **********************
         */
/*
        // 单独端口开启TCP服务器，添加子服务。
        $server = ServerManager::getInstance()->getSwooleServer();

        // Mqtt服务
        $mqttServer = $server->addlistener('0.0.0.0', 8102, SWOOLE_TCP);
        $mqttServer->set([
            'open_length_check' => false, //不验证数据包
            'open_mqtt_protocol' => true, //支持解析mqtt协议

            'heartbeat_check_interval' => 150,
            'heartbeat_idle_time' => 300,
        ]);
        $mqttServer->on('Connect', '\\App\\TcpServer\\Mqtt\\MqttEvent::connect');
        $mqttServer->on('Receive', '\\App\\TcpServer\\Mqtt\\MqttEvent::receive');
        $mqttServer->on('Close', '\\App\\TcpServer\\Mqtt\\MqttEvent::close');

        //Tcp服务
        $subPort1 = $server->addlistener('0.0.0.0', 8101, SWOOLE_TCP);
        $subPort1->set([
            'open_length_check' => false, //不验证数据包
        ]);
        $subPort1->on('connect', function (\Swoole\Server $server, int $fd, int $reactor_id) {
//            echo "fd:{$fd} 已连接\n";
//            $str = '恭喜你连接成功';
//            $server->send($fd, $str);
        });
        $subPort1->on('close', function (\Swoole\Server $server, int $fd, int $reactor_id) {
//            echo "fd:{$fd} 已关闭\n";
        });
        $subPort1->on('receive', function (\Swoole\Server $server, int $fd, int $reactor_id, string $data) {
//            echo "fd:{$fd} 发送消息:{$data}\n";
        });
*/

    }
}

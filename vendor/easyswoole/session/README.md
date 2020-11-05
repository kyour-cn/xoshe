# session
测试代码

```
use EasySwoole\Session\FileSessionHandler;
use EasySwoole\Session\Test\RedisHandler;
use EasySwoole\Session\AbstractSessionController;

class Redis extends AbstractSessionController
{

    protected function sessionHandler(): \SessionHandlerInterface
    {
        /*
         * 此处应该由连接池拿链接，否则实际生产会导致不断创建链接
         */
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        return new RedisHandler($redis);
    }

    function index()
    {
        $this->session()->start();
        $time = $this->session()->get('test');
        if($time){
            $this->response()->write('session time is '.$time);
        }else{
            $this->session()->set('test',time());
            $this->response()->write('session time is new set');
        }
    }
}

class Index extends AbstractSessionController
{

    protected function sessionHandler(): \SessionHandlerInterface
    {
        return new FileSessionHandler();
    }

    function index()
    {
        $this->session()->start();
        $time = $this->session()->get('test');
        if($time){
            $this->response()->write('session time is '.$time);
        }else{
            $this->session()->set('test',time());
            $this->response()->write('session time is new set');
        }
    }
}

$http = new \swoole_http_server("0.0.0.0", 9501);
$http->set([
    'worker_num'=>1
]);

$service = new \EasySwoole\Http\WebService();

$http->on("request", function ($request, $response)use($service) {
    $req = new \EasySwoole\Http\Request($request);
    $service->onRequest($req,new \EasySwoole\Http\Response($response));
});

$http->start();
```

> 自带的文件session实现是无锁的
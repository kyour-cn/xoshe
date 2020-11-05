# EasySwoole HTTP 服务组件

一个轻量级的HTTP Dispatch组件
## Server Script
```
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\Controller;

require_once 'vendor/autoload.php';


class Index extends Controller
{

    function index()
    {
        // TODO: Implement index() method
        $this->response()->write('hello world');
        $this->response()->setCookie('a','a',time()+3600);
    }

    function testSession()
    {
        $this->session()->start();
        $this->session()->set('a',time());
    }

    function testSession2()
    {
        $this->session()->start();
        $this->response()->write($this->session()->get('a'));
    }

    function testException()
    {
        new NoneClass();
    }

    protected function onException(\Throwable $throwable): void
    {
        $this->response()->write($throwable->getMessage());
    }

    protected function gc()
    {
        parent::gc();
        var_dump('class :'.static::class.' is recycle to pool');
    }
}


$http = new \Swoole\Http\Request("0.0.0.0", 9501);
$http->set([
    'worker_num'=>1
]);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$service = new \EasySwoole\Http\WebService();
$service->setExceptionHandler(function (\Throwable $throwable,\EasySwoole\Http\Request $request,\EasySwoole\Http\Response $response){
    $response->write('error:'.$throwable->getMessage());
});

$http->on("request", function ($request, $response)use($service) {
    $req = new \EasySwoole\Http\Request($request);
    $service->onRequest($req,new \EasySwoole\Http\Response($response));
});

$http->start();
```


## test
```
use EasySwoole\Http\Annotation\Method;

class Test extends \EasySwoole\Http\AbstractInterface\AnnotationController
{
    /**
     * @\EasySwoole\Http\Annotation\Context(key="MYSQL")
     */
    public $mysql;

    /**
     * @var
     * @\EasySwoole\Http\Annotation\DI(key="IOC")
     */
    public $IOC;

    function index()
    {
        // TODO: Implement index() method.
    }

    /**
     * @Method(allow={GET,POST})
     * @\EasySwoole\Http\Annotation\Param(name="test",from={POST})
     * @\EasySwoole\Http\Annotation\Param(name="msg",alias="消息字段",lengthMax="20|消息过长",required="消息不能为空")
     * @\EasySwoole\Http\Annotation\Param(name="type",inArray="{1,2,3,4}")
     */
    function fuck($test,$msg)
    {
        var_dump($test,$msg);
    }

    protected function onException(\Throwable $throwable): void
    {
        if($throwable instanceof \EasySwoole\Http\Exception\ParamAnnotationValidateError){
            var_dump($throwable->getValidate()->getError()->getErrorRuleMsg());
        }else{
            var_dump($throwable->getMessage());
        }
    }

}

$request = new \EasySwoole\Http\Request();
$request->withQueryParams([
    'msg'=>"this is msg",
    'type'=>1
]);

$request->withMethod("get");
$response = new \EasySwoole\Http\Response();
$test = new Test();
$test->__hook('fuck',$request,$response);
```

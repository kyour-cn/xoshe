<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/24
 * Time: 下午2:56
 */

namespace EasySwoole\Http;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Exception\ControllerPoolEmpty;
use EasySwoole\Http\Exception\RouterError;
use EasySwoole\Http\Message\Status;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\Dispatcher as RouterDispatcher;
use FastRoute\RouteCollector;
use Swoole\Coroutine\Channel;

class Dispatcher
{
    private $router = null;
    /**
     * @var AbstractRouter|null
     */
    private $routerRegister = null;
    private $routerMethodNotAllowCallBack;
    private $routerNotFoundCallBack;
    private $globalModel;
    private $controllerNameSpacePrefix;
    private $maxDepth;
    private $maxPoolNum;
    private $controllerPoolCreateNum = [];
    private $httpExceptionHandler = null;
    private $controllerPoolWaitTime = 5.0;
    private $pathInfoMode = true;

    function __construct(string $controllerNameSpace,int $maxDepth = 5,int $maxPoolNum = 200)
    {
        $this->controllerNameSpacePrefix = trim($controllerNameSpace,'\\');
        $this->maxPoolNum = $maxPoolNum;
        $this->maxDepth = $maxDepth;
    }

    /**
     * @param float $controllerPoolWaitTime
     */
    public function setControllerPoolWaitTime(float $controllerPoolWaitTime): void
    {
        $this->controllerPoolWaitTime = $controllerPoolWaitTime;
    }


    function setHttpExceptionHandler(callable $handler):void
    {
        $this->httpExceptionHandler = $handler;
    }



    /**
     * @param mixed $routerMethodNotAllowCallBack
     */
    public function setRouterMethodNotAllowCallBack($routerMethodNotAllowCallBack): void
    {
        $this->routerMethodNotAllowCallBack = $routerMethodNotAllowCallBack;
    }

    /**
     * @param mixed $routerNotFoundCallBack
     */
    public function setRouterNotFoundCallBack($routerNotFoundCallBack): void
    {
        $this->routerNotFoundCallBack = $routerNotFoundCallBack;
    }

    /**
     * @return false|null|AbstractRouter
     */
    function getRouterRegister()
    {
        return $this->routerRegister;
    }

    public function initRouter(?string $routerClass = null,bool $newInstance = false):?AbstractRouter
    {
        if($this->routerRegister && !$newInstance){
            return $this->routerRegister;
        }
        if($routerClass){
            $class = $routerClass;
        }else{
            $class = $this->controllerNameSpacePrefix.'\\Router';
        }
        try{
            if(class_exists($class)){
                $ref = new \ReflectionClass($class);
                if($ref->isSubclassOf(AbstractRouter::class)){
                    $this->routerRegister = $ref->newInstance();
                    if($this->routerRegister->getMethodNotAllowCallBack()){
                        $this->routerMethodNotAllowCallBack = $this->routerRegister->getMethodNotAllowCallBack();
                    }
                    if($this->routerRegister->getRouterNotFoundCallBack()){
                        $this->routerNotFoundCallBack = $this->routerRegister->getRouterNotFoundCallBack();
                    }
                    $this->globalModel = $this->routerRegister->isGlobalMode();
                    $this->pathInfoMode = $this->routerRegister->isPathInfoMode();
                }else{
                    throw new RouterError("class : {$class} not AbstractRouter class");
                }
            }
        }catch (\Throwable $throwable){
            throw $throwable;
        }
        return $this->routerRegister;
    }

    public function dispatch(Request $request,Response $response):void
    {
        /*
         * 进行一次初始化判定
         */
        if($this->router === null){
            $r = $this->initRouter();
            if($r){
                $data = $r->getRouteCollector()->getData();
                if(!empty($data)){
                    $this->router = new GroupCountBased($data);
                }else{
                    $this->router = false;
                }
            }else{
                $this->router = false;
            }
        }
        $path = UrlParser::pathInfo($request->getUri()->getPath());
        if($this->router instanceof GroupCountBased){
            if($this->pathInfoMode){
                $routerPath = $path;
            }else{
                $routerPath = $request->getUri()->getPath();
            }
            $handler = null;
            $routeInfo = $this->router->dispatch($request->getMethod(),$routerPath);
            if($routeInfo !== false){
                switch ($routeInfo[0]) {
                    case RouterDispatcher::FOUND:{
                        $handler = $routeInfo[1];
                        //合并解析出来的数据
                        $vars = $routeInfo[2];
                        $data = $request->getQueryParams();
                        $request->withQueryParams($vars+$data);
                        break;
                    }
                    case RouterDispatcher::METHOD_NOT_ALLOWED:{
                        $handler = $this->routerMethodNotAllowCallBack;
                        break;
                    }
                    case RouterDispatcher::NOT_FOUND:
                    default:{
                        $handler = $this->routerNotFoundCallBack;
                        break;
                    }
                }
            }
            //如果handler不为null，那么说明，非为 \FastRoute\Dispatcher::FOUND ，因此执行
            if(is_callable($handler)){
                try{
                    //若直接返回一个url path
                    $ret = call_user_func($handler,$request,$response);
                    if(is_string($ret)){
                        $path = UrlParser::pathInfo($ret);
                    }else if($ret == false){
                        return;
                    }else{
                        //可能在回调中重写了URL PATH
                        $path = UrlParser::pathInfo($request->getUri()->getPath());
                    }
                    $request->getUri()->withPath($path);
                }catch (\Throwable $throwable){
                    $this->hookThrowable($throwable,$request,$response);
                    //出现异常的时候，不在往下dispatch
                    return;
                }
            }else if(is_string($handler)){
                $path = UrlParser::pathInfo($handler);
                $request->getUri()->withPath($path);
                goto response;
            }
            /*
                * 全局模式的时候，都拦截。非全局模式，否则继续往下
            */
            if($this->globalModel){
                return;
            }
        }
        response:{
        $this->controllerHandler($request,$response,$path);
    }
    }

    private function controllerHandler(Request $request,Response $response,string $path)
    {
        $pathInfo = ltrim($path,"/");
        $list = explode("/",$pathInfo);
        $actionName = null;
        $finalClass = null;
        $controlMaxDepth = $this->maxDepth;
        $currentDepth = count($list);
        $maxDepth = $currentDepth < $controlMaxDepth ? $currentDepth : $controlMaxDepth;
        while ($maxDepth >= 0){
            $className = '';
            for ($i=0 ;$i<$maxDepth;$i++){
                $className = $className."\\".ucfirst($list[$i] ?: 'Index');//为一级控制器Index服务
            }
            if(class_exists($this->controllerNameSpacePrefix.$className)){
                //尝试获取该class后的actionName
                $actionName = empty($list[$i]) ? 'index' : $list[$i];
                $finalClass = $this->controllerNameSpacePrefix.$className;
                break;
            }else{
                //尝试搜搜index控制器
                $temp = $className."\\Index";
                if(class_exists($this->controllerNameSpacePrefix.$temp)){
                    $finalClass = $this->controllerNameSpacePrefix.$temp;
                    //尝试获取该class后的actionName
                    $actionName = empty($list[$i]) ? 'index' : $list[$i];
                    break;
                }
            }
            $maxDepth--;
        }

        if(!empty($finalClass)){
            try{
                $controllerObject = $this->getController($finalClass);
            }catch (\Throwable $throwable){
                $this->hookThrowable($throwable,$request,$response);
                return;
            }
            if($controllerObject instanceof Controller){
                try{
                    $forward = $controllerObject->__hook($actionName,$request,$response);
                    if(is_string($forward) && (strlen($forward) > 0) && ($forward != $path)){
                        $forward = UrlParser::pathInfo($forward);
                        $request->getUri()->withPath($forward);
                        $this->dispatch($request,$response);
                    }
                }catch (\Throwable $throwable){
                    $this->hookThrowable($throwable,$request,$response);
                }finally {
                    $this->recycleController($finalClass,$controllerObject);
                }
            }else{
                $throwable = new ControllerPoolEmpty('controller pool empty for '.$finalClass);
                $this->hookThrowable($throwable,$request,$response);
            }
        }else{
            $response->withStatus(404);
            $response->write("not controller class match");
        }
    }

    protected function getController(string $class)
    {
        $classKey = $this->generateClassKey($class);
        if(!isset($this->$classKey)){
            $this->$classKey = new Channel($this->maxPoolNum+1);
            $this->controllerPoolCreateNum[$classKey] = 0;
        }
        $channel = $this->$classKey;
        //懒惰创建模式
        /** @var Channel $channel */
        if($channel->isEmpty()){
            $createNum = $this->controllerPoolCreateNum[$classKey];
            if($createNum < $this->maxPoolNum){
                $this->controllerPoolCreateNum[$classKey] = $createNum+1;
                try{
                    //防止用户在控制器结构函数做了什么东西导致异常
                    return new $class();
                }catch (\Throwable $exception){
                    $this->controllerPoolCreateNum[$classKey] = $createNum;
                    //直接抛给上层
                    throw $exception;
                }
            }
            return $channel->pop($this->controllerPoolWaitTime);
        }
        return $channel->pop($this->controllerPoolWaitTime);
    }

    protected function recycleController(string $class,Controller $obj)
    {
        $classKey = $this->generateClassKey($class);
        /** @var Channel $channel */
        $channel = $this->$classKey;
        $channel->push($obj);
    }

    protected function hookThrowable(\Throwable $throwable,Request $request,Response $response)
    {
        if(is_callable($this->httpExceptionHandler)){
            call_user_func($this->httpExceptionHandler,$throwable,$request,$response);
        }else{
            $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
            $response->write(nl2br($throwable->getMessage()."\n".$throwable->getTraceAsString()));
        }
    }

    protected function generateClassKey(string $class):string
    {
        return substr(md5($class), 8, 16);
    }
}
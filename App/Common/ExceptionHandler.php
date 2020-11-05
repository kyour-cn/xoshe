<?php
namespace App\Common;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Http\Message\Status;

class ExceptionHandler
{
    public static function handle( \Throwable $exception, Request $request, Response $response )
    {
        $debug = config('app.debug',false);

        $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
        if($debug){
            $response->write(nl2br($exception->getMessage()."\n".$exception->getTraceAsString()));
        }else{
            $response->write('System Error.');
        }

        // echo "====================================\n";
        // var_dump($exception->getTraceAsString());
    }
}
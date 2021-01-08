<?php declare(strict_types=1);
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

    //入口脚本
    public function script()
    {
        $header = $this->request()->getHeaders();

        $conf = config('app');

        if(isset($header['referer']) and isset($header['referer'][0])){
            $arr = parse_url($header['referer'][0]);
            $host = $arr['host'];
        }else{
            $host = $conf['host'];
        }

        if($conf['res_host'] == 'local'){
            $conf['res_host'] = $host;
        }

        $odos = \App\Common\Plugin\Iciba::oneDayOneSentence();

        $ad = [
            'title' => '小猿社',
            'content' => '优质同城兴趣社区',
            'pic' => $odos['fenxiang_img'],
            'bgpic' => $odos['fenxiang_img'], //picture2
            'countdown' => 2
        ];

        $s = '
        App.name = "小猿社";
        App.host = "'.$host.'";
        App.res = "http://'.$conf['res_host'].'/";

        App.temp.adData = '.json_encode($ad).';
        ';

        $this->write($s);
    }

    //每日一句
//    public function oneDayOneSentence(bool $isret = false)
//    {
//        $body = \App\Common\Plugin\Iciba::oneDayOneSentence();
//
//        $this->withData(0, 'success', $body);
//    }

}

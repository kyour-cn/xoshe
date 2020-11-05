<?php declare(strict_types=1);
namespace App\HttpController\Index;

// use App\Common\Utils;
use EasySwoole\Validate\Validate;
use App\Model\Article;
use App\Model\User as UserModel;

class Index extends BaseAuth
{

    //项目首页
    public function index()
    {
        //读取静态文件返回
        $file = EASYSWOOLE_ROOT . '/Public/index.html';
        $this->response()->write(file_get_contents($file));
    }

    //二维码测试
    public function qrcode()
    {
        $qrCode = new \Endroid\QrCode\QrCode('Life is too short to be generating QR codes');

        // header('Content-Type: '.$qrCode->getContentType());
        $this->response()->withHeader('Content-type',$qrCode->getContentType());
        $this->write($qrCode->writeString());

    }

    //短信验证码
    public function sendVerCode()
    {
        $post = $this->input('post');

        $ip = $this->request()->getHeader('x-real-ip')[0];
        $post['ip'] = $ip;

        $valitor = new Validate();
        $valitor->addColumn('phone')
        ->required('手机号不能为空')
        ->integer('手机号必须为整数')
        ->length(11, '手机号只能为11位');
        $valitor->addColumn('imgcode')
        ->length(4, '请正确填写图形验证码')
        ->required('图形验证码不能为空');

        if(!$valitor->validate($post)){
            $msg = $valitor->getError()->__toString();
            return $this->withData(1, $msg, $post);
        }

        if($post['imgcode'] != $this->session('img_code')){
            return $this->withData(1, '图形验证码不正确', $post);
        }

        //生成随机验证码
        $veriNum = mt_rand(100000,999999);
        \App\Common\Extend\Message::alimsg($post['phone'], 'SMS_176885217', [
            'code' => $veriNum
        ]);

        $this->withData(0, '发送成功', $post);

        $this->session('phone_code_'.$post['phone'], $veriNum);

        \App\Model\MsgRecord::create([
            'ip' => $ip,
            'phone' => $post['phone'],
            'time' => time(),
            'code' => $veriNum,
            'ymd' => date('Ymd')
        ])->save();

    }

    //图形验证码
    public function imgcode()
    {
        //生成随机验证码
        $veriNum = mt_rand(1000,9999);

        $vConf = new \EasySwoole\VerifyCode\Conf();
        $vConf->setUseNoise();
        $code = new \EasySwoole\VerifyCode\VerifyCode($vConf);
        
        $draw = $code->DrawCode($veriNum);

        $this->response()->withHeader('Content-Type','image/png');
        $this->response()->write($draw->getImageByte());

        $this->session('img_code',$veriNum);
    }

    //语音合成
    public function tts()
    {
        // require_once 'AipSpeech.php';

// 你的 APPID AK SK
$appId = '15589554';
$appKey = '6OyjIGp6tZszfuTuLh8cQzvY';
$appSecret = '8AXVW7FbHd5V5BTIwrl0ZhzOGDTOZsO3';

$client = new \App\Common\Extend\BaiduTTS\AipSpeech($appId, $appKey, $appSecret);

$result = $client->synthesis('皮皮搞笑，温暖快乐的家', 'zh', 1, [
    'vol' => 5,
    // 'pit' => 3,
    'per' => '5118'
]);

// 识别正确返回语音二进制 错误则返回json 参照下面错误码
if(!is_array($result)){
    $this->response()->withHeader('Content-Type','image/mpeg');
    $this->response()->withHeader('Accept-Ranges','bytes');

    $this->write($result);

    // file_put_contents('audio.mp3', $result);
}

    }

    // 容器测试
    public function test()
    {
        $get = container('test');

        if(!$get){
            $get = time();
            container('test',$get);
        }
        
        $this->write($get);
    }

}

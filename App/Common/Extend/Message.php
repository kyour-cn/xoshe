<?php declare(strict_types=1);
namespace App\Common\Extend;

use AlibabaCloud\Client\AlibabaCloud;

/**
 * 短信集成功能
 * 用法 \App\Common\Extend\Message::[actionName]();
 */
class Message
{

    //阿里短信发送
    static public function alimsg($phone,$templat = "SMS_176885217",$param = [])
    {
        $conf = config('app.dev_alimsg');
        AlibabaCloud::accessKeyClient($conf['AccessKey'], $conf['Secret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {

            $result = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->host('dysmsapi.aliyuncs.com')
            ->options([
                'query' => [
                  'RegionId' => "cn-hangzhou",
                  'PhoneNumbers' => $phone,
                  'SignName' => $conf['SignName'],
                  'TemplateCode' => $templat,
                  'TemplateParam' => json_encode($param),
                ],
            ])
            ->request();

            $arr = $result->toArray();
            if($arr['Code'] == 'OK'){
                $res['status'] = 1;
                $res['message'] = '短信发送成功,请注意查收！';
            }else{
                $res['status'] = 0;
                $res['message'] = '短信发送失败！';
            }
            $res['result'] = $arr;
            //返回状态
            return $res;
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['message'] = '短信发送失败！';
            $res['err'] = $e->getMessage();
            $res['result'] = [];
            //返回状态
            return $res;
        }
    }
    
}
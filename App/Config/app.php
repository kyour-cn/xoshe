<?php

/**
 * 应用的配置信息
 * 通过config('app.xx')获取
 */
return [
    //系统时区
    'timezone' => 'Asia/Shanghai',
    //启用调试模式
    'debug' => true,

    //===========其他配置
    //阿里短信
    'dev_alimsg' => [
        'AccessKey' => '***',
        'Secret'    => '***',
        'SignName'  => '***'
    ],
    //阿里oss
    'dev_alioss' => [
        'AccessKey'   => '***',
        'Secret'      => '***',
        'Endpoint'    => "http://oss-cn-chengdu.aliyuncs.com",
        'Bucket'      => '***'
    ]

];

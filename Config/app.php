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

    //===========其他配置===========

    //应用名 -保证唯一性，避免在同一机器上运行相同的应用
    'app_name' => 'xoshe',

    //服务器域名
    'host' => 'xoshe.cn',
    'res_host' => 'ac.xxx.cn',

    //资源储存方式 local|oss|api
    'res_type' => 'api',

    //资源api服务器接口配置 -若res_type=api生效
    'dev_res_api' => [
<<<<<<< HEAD:Config/app.php
        'Host' => 'ac.x***.cn',
        'User' => 'xxx',
        'Secret' => 'xxxxx'
    ],

    //阿里oss配置 -若res_type=oss生效
    'dev_alioss' => [
        'AccessKey'   => 'LTAI*******jhosZh',
        'Secret'      => 'NSc3*******gknnfn7z5',
        'Endpoint'    => "http://oss-cn-chengdu.aliyuncs.com",
        'Bucket'      => '***'
=======
        'Host' => 'xxx.xoshe.cn',
        'User' => 'xoshe',
        'Secret' => 'xxxxx'
>>>>>>> 7793ae38bc860a73ea459447ea7b38bccabead89:App/Config/app.php
    ],

    //阿里短信配置
    'dev_alimsg' => [
<<<<<<< HEAD:Config/app.php
        'AccessKey' => 'LTA********osZh',
        'Secret'    => 'NSc3HE*******nnfn7z5',
        'SignName'  => '皮小猿'
=======
        'AccessKey' => 'xxxx',
        'Secret'    => 'xxxxxxxxx',
        'SignName'  => '皮小猿'
    ],

    //阿里oss配置
    'dev_alioss' => [
        'AccessKey'   => 'xxxx',
        'Secret'      => 'xxxxxxxxx',
        'Endpoint'    => "http://oss-cn-chengdu.aliyuncs.com",
        'Bucket'      => 'xxxx'
>>>>>>> 7793ae38bc860a73ea459447ea7b38bccabead89:App/Config/app.php
    ]

];

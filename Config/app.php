<?php
/**
 * 应用的配置信息
 * 通过config('app.xx')获取
 */
return [
    // 系统时区
    'timezone' => 'Asia/Shanghai',
    // 启用调试模式
    'debug' => true,

    //===========其他配置===========

    // 应用名 -保证唯一性，避免在同一机器上运行相同的应用
    'app_name' => 'xoshe',

    // 服务器域名
    'host' => 'xoshe.cn',
    // 资源服务器域名
    'res_host' => 'ac.xoshe.cn',

    // 阿里短信配置
    'dev_alimsg' => [
        'AccessKey' => 'LTAxxxosZh',
        'Secret'    => 'NScxxxxxxxfn7z5',
        'SignName'  => '皮小猿'
    ]
];

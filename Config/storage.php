<?php

/**
 * 资源储存配置文件
 * 通过config('storage.xx')获取
 */
return [

    //资源储存方式 local|alioss|api
    'drive' => 'api',

    //资源api服务器接口配置 -若res_type=local生效
    'dev_local' => [
        // 资源目录路径 以/结尾
        'public' => EASYSWOOLE_ROOT. '/Public/'
    ],

    //资源api服务器接口配置 -若res_type=api生效
    // 接口服务端代码：https://github.com/php-sword/storage/blob/main/tests/api.php
    'dev_api' => [
        'Host' => 'xxx.xoshe.cn',
        'Port' => 80,
        'Gateway' => '/api.php',
        'User' => 'xxx',
        'Secret' => 'kxxxwd',
        'OutTime' => -1
    ],

    // 阿里oss配置 -若res_type=alioss生效
    'dev_alioss' => [
        'AccessKey'   => 'LTAxxxxxsZh',
        'Secret'      => 'NSc3Hxxxxxnfn7z5',
        'Endpoint'    => "http://oss-cn-chengdu.aliyuncs.com",
        'Bucket'      => 'xxx'
    ],

];

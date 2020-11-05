<?php
/**
 * Session服务配置
 */
return [
    'type'        => 'redis', //储存驱动 redis、file
    'sessionName' => 'sessionId', //Session的CookieName
    'expire'      => 86400 * 7, //过期时间 s
];

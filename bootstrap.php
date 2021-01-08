<?php
//全局bootstrap事件

//触发sword事件
\Sword\SwordEvent::bootstrap();

// ======================= 应用代码 =======================

/**
 * **************** Redis 缓存清理 **********************
 * 清理swc_开头的redis键值
 */
(function () {
    $r_conf = config('redis');

    //删除wsc开头的临时缓存
    $redis = new \Redis();
    $redis->connect($r_conf['host'], $r_conf['port']);
    if (!empty($r_conf['password']))
        $redis->auth($r_conf['password']); //密码验证

    $iterator = null;
    $i = 0;//计数
    while (true) {
        $keys = $redis->scan($iterator, 'swc_*');
        if ($keys === false) {
            //结束，未找到匹配pattern的key
//            echo "del caches ok,count:$i\n";
            $redis->close();
            return; //退出
        }
        foreach ($keys as $key) {
            $i++;
            $redis->del($key);
        }
    }
})();

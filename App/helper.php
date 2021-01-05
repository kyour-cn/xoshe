<?php declare(strict_types=1);

use App\Common\Utils;

define('SWORD_NULL', "SWORD_NULL_VALUE");

define('SWORD_REDIS_EXISTS', "SWORD_REDIS_EXISTS_VALUE");

if (!function_exists('container')) {
    /**
     * 获取容器中App实例
     * @param string $name
     * @param string $value
     * @return array|bool|mixed|null
     */
    function container($name = SWORD_NULL, $value = SWORD_NULL)
    {
        $ins = App\Common\Container::getInstance();

        // 无参数时获取所有
        if($name === SWORD_NULL or is_null($name)){
            return $ins->get();
        }

        //取容器中某一个实例,使用SWORD_NULL是为了避免value传入的是null
        if($value === SWORD_NULL){
            return $ins->get($name);
        }

        //设置容器
        $ins->set($name,$value);

        return true;
    }
}

if (!function_exists('config')) {
    /**
     * 获取容器中配置文件
     * @param string $name
     * @param null $default
     * @return array|mixed|null
     */
    function config(string $name = SWORD_NULL, $default = null)
    {
        $ins = App\Common\Container::getInstance();

        $config = $ins->get('sword_config')?:[];

        // 未初始化配置 -加载config文件夹的配置
        if(!$config){
            $path = EASYSWOOLE_ROOT .'/Config';
            //取出配置目录全部文件
            foreach(scandir($path) as $file){
                //如果是php文件
                if(preg_match('/.php/',$file)){
                    //获取配置内容
                    $arr = require $path . DIRECTORY_SEPARATOR . $file;
                    //存入数组
                    $config[strtolower(basename($file,".php"))] = $arr;
                }
            }
        }
        $ins->set('sword_config', $config);

        // 无参数时获取所有
        if ($name === SWORD_NULL) {
            return $config;
        }

        if (false === strpos($name, '.')) {
            return $config[$name];
        }

        $name    = explode('.', $name);
        $name[0] = strtolower($name[0]);

        // 按.拆分成多维数组进行判断
        foreach ($name as $val) {
            if (isset($config[$val])) {
                $config = $config[$val];
            } else {
                return $default;
            }
        }

        return $config;
    }

}

if (!function_exists('cache')) {
    /**
     * redis缓存工具
     * @param mixed $name
     * @param string $value
     * @param mixed $expire
     * @return bool|mixed|null
     * @throws \EasySwoole\Redis\Exception\RedisException
     */
    function cache($name = null, $value = SWORD_NULL, $expire = null)
    {

        // 无参数时
        if(is_null($name)){
            return null;
        }
        $redis = Utils::getRedis();

        //判断是否存在
        if($value == SWORD_REDIS_EXISTS){
            return $redis->exists($name);
        }

        //删除数据
        if($value === null){
            $redis->del($name);
            return true;
        }

        //取数据
        if($value === SWORD_NULL){
            $v = $redis->get($name);
            return $v === null ? $v : unserialize($v);
        }

        //设置
        if($expire){
            $redis->set($name, serialize($value), $expire);
        }else{
            $redis->set($name, serialize($value));
        }

        return true;

    }
}

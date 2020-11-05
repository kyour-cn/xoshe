<?php declare(strict_types=1);

define('SWORD_NULL',"SWORD_NULL_VALUE");

//容器
// $sword_container = [];

if (!function_exists('container')) {
    /**
     * 获取容器中App实例
     */
    function container($name = SWORD_NULL, $value = SWORD_NULL)
    {

        $ins = App\Common\Container::getInstance();

        // global $sword_container;

        // 无参数时获取所有
        if($name === SWORD_NULL or is_null($name)){
            return $ins->get();
        }

        //取容器中某一个实例,使用SWORD_NULL是为了避免value传入的是null
        if($value === SWORD_NULL){
            return $ins->get($name);
            // return isset($sword_container[$name])?$sword_container[$name]:null;
        }

        //设置容器
        $ins->set($name,$value);
        // $sword_container[$name] = $value;

        return true;

    }
}

if (!function_exists('config')) {
    /**
     * 获取容器中配置文件
     */
    function config(string $name = SWORD_NULL, $default = null)
    {
        $ins = App\Common\Container::getInstance();

        // global $sword_container;

        $config = $ins->get('sword_config')?:[];
        // $sword_container['sword_config']??[];

        // 未初始化配置 -加载config文件夹的配置
        if(!$config){
            $path = EASYSWOOLE_ROOT .'/App/Config';
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
     */
    function cache($name = null, $value = SWORD_NULL, $expire = null)
    {

        // 无参数时
        if(is_null($name)){
            return null;
        }
        $redis = \App\Common\Utils::getRedis();

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

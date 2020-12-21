<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class Setting
 */
class Setting extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'setting';

    /**
     * 取出data字段的json反序列化数组
     * @return mixed
     */
    public function getData()
    {
        return json_decode($this->data, true);
    }

    /**
     * 获取数据库设置
     * @param string $name 主键名
     * @param bool $noCache 不使用缓存
     * @param int $cacheExpire 缓存过期时间-秒
     * @return array
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    static public function fetch(string $name = 'site', bool $noCache = false, int $cacheExpire = 10)
    {
        if(!$noCache){
            $c_key = 'dbcache.setting.'.config('db.name').'.'.$name;
            if($cache = cache($c_key)){
                return $cache;
            }
        }
        $data = self::create()->get($name);
        if($data){
            $data = $data->getData();
            if(!$noCache) {
                cache($c_key, $data, $cacheExpire);
            }
            return $data;
        }else{
            return [];
        }
    }

}
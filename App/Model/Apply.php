<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;

/**
 * Class Log
 */
class Apply extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'apply';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值
     */
    protected function getDataAttr($value, $data)
    {
        return json_decode($value, true);
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值
     */
    protected function setDataAttr($value, $data)
    {
        if(is_array($value)){
            $value = json_encode($value);
        }
        return $value;
    }

    //模型关联
    public function user(string $fields = 'id,nickname,avatar')
    {
        return $this->hasOne(User::class, function(QueryBuilder $query) use($fields){
            $query->fields($fields);
            return $query;
        }, 'uid', 'id');
    }

}
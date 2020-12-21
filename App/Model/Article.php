<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;
use EasySwoole\Mysqli\QueryBuilder;
/**
 * Class Article
 */
class Article extends AbstractModel
{

    /**
      * @var string 
      */
    protected $tableName = 'article';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function setTitleAttr($value, $data)
    {
        //最长200
        if(strlen($value) > 200){
            return mb_substr($value, 0 ,200) .'...';
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

    public function topic(string $fields = 'id,name,icon,model')
    {
        return $this->hasOne(Topic::class, function(QueryBuilder $query) use($fields){
            $query->fields($fields);
            return $query;
        }, 'pid', 'id');
    }
}

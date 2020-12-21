<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;

/**
 * Class Comment
 */
class Comment extends AbstractModel
{

    /**
      * @var string 
      */
    protected $tableName = 'comment';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = false;

    //模型关联
    public function user(string $fields = 'id,nickname,avatar')
    {
        return $this->hasOne(User::class, function(QueryBuilder $query) use($fields){
            $query->fields($fields);
            return $query;
        }, 'uid', 'id');
    }
}
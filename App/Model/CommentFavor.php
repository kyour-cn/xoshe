<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;

/**
 * Class CommentFavor
 */
class CommentFavor extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'comment_favor';

    //模型关联
    public function comment(string $fields = '*')
    {
        return $this->hasOne(Comment::class, function(QueryBuilder $query) use($fields){
            $query->fields($fields);
            return $query;
        }, 'tid', 'id');
    }
}
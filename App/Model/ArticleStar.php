<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class ArticleStar
 */
class ArticleStar extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'article_star';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'time';
    protected $updateTime = false;

}
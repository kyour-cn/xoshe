<?php
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class ArticleFavor
 */
class ArticleFavor extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'article_favor';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'time';
    protected $updateTime = false;

}
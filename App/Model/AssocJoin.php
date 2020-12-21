<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class AssocJoin
 */
class AssocJoin extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'assoc_join';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = false;

}

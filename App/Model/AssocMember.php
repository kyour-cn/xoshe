<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class AssocMember
 */
class AssocMember extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'assoc_member';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'join_time';
    protected $updateTime = false;

}
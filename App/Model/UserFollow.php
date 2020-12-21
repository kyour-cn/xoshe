<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class UserFollow
 */
class UserFollow extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'user_follow';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'time';
    protected $updateTime = false;

}

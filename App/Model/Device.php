<?php declare(strict_types=1);
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class Device
 */
class Device extends AbstractModel
{

    /**
     * @var string
     */
    protected $tableName = 'device';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'online_time';

}
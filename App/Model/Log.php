<?php
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class Log
 */
class Log extends AbstractModel
{

    /**
      * @var string 
      */
    protected $tableName = 'log';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'time';
    protected $updateTime = false;

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function setDateAttr($value, $data)
    {
        return date('Y-m-d H:i:s');
    }
}
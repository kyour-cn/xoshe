<?php
namespace App\Model;

use EasySwoole\ORM\AbstractModel;

/**
 * Class User
 */
class User extends AbstractModel
{
    /**
     * 数据表名
     * @var string 
     */
    protected $tableName = 'user';

    // 自动时间戳
    protected $autoTimeStamp = true;
    protected $createTime = 'register_time';
    protected $updateTime = false;

    // 字段预定义属性
    // protected $casts = [
    //     'nickname' => 'string',
    //     'info'     => 'json'
    // ];

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function getInfoAttr($value, $data)
    {
        $val = [];
        if($value){
            $val = json_decode($value);
        }
        return $val;
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function getBornAttr($value, $data)
    {
        $data['born_ymd'] = date('Y/m/d', $value);
        return date('Y-m-d', $value);
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function setBornAttr($value, $data)
    {
        return strtotime($value);
    }
}

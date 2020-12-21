<?php declare(strict_types=1);
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

    public function default()
    {
//        TimeStamp::
        $this->field('id,nickname,num,avatar,register_time,info,born,sex,level,sign,status');
        return $this;
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function getInfoAttr($value, $data)
    {
        $val = [];
        if($value){
            $val = json_decode($value, true);
        }
        return $val;
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值
     */
    protected function setInfoAttr($value, $data)
    {
        if(is_string($value)) return $value;
        return json_encode($value);
    }

    /**
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
//    protected function getBornAttr($value, $data)
//    {
//        $data['born_ymd'] = date('Y/m/d', $value);
//        return date('Y-m-d', $value);
//    }

    /**
     * 转换时间戳
     * $value mixed 是原值
     * $data  array 是当前model所有的值 
     */
    protected function setBornAttr($value, $data)
    {
        if(is_numeric($value)) return $value;
        return strtotime($value);
    }

    /**
     * 快捷设置Info字段内数据
     * @param $data array 数组数据
     * @param bool $re_query 是否重查
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function setInfo(array $data, bool $re_query = false)
    {
        if(empty($this->info) or $re_query){
            //重查
            $info = User::create()->where('id', $this->id)->val('info');
        }else{
            $info = $this->info;
        }

        foreach($data as $k => $v){
            if(is_array($v)){
                switch($v[1]){
                    case 'add':
                        if(!isset($info[$k])) $info[$k] = 0;
                        $info[$k] += $v[0];
                        break;
                    case 'sub':
                        if(!isset($info[$k])) $info[$k] = 0;
                        $info[$k] -= $v[0];
                        break;
                }
            }else{
                $info[$k] = $v;
            }
        }

        return $this->update(['info' => $info]);
    }

}

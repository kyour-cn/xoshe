<?php
namespace App\Common;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

class Db extends QueryBuilder
{

    private $tableName;

    public static function new(string $table = '')
    {
        return (new static())->table($table);
    }

    public function table(string $table)
    {
        $this->tableName = $table;
        return $this;
    }
    
    //使where支持数组
    public function where($whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {

        if(is_array($whereProp)){
            foreach ($whereProp as $v) {
                parent::where($v[0], $v[1], $v[2]?? '=', $v[3]?? 'AND');
            }
        }else{
            parent::where($whereProp, $whereValue, $operator, $cond);
        }
        return $this;
    }

    public function getSql($action = 'find') :string
    {

        $this->getOne($this->tableName);
        return $this->getLastPrepareQuery();
    }

    public function find()
    {
        $this->getOne($this->tableName);
        $data = DbManager::getInstance()->query($this, true, 'default');
        $result = $data->getResultOne();
        return $result;
    }

    public function findAll()
    {
        $this->get($this->tableName);
        $data = DbManager::getInstance()->query($this, true, 'default');
        $result = $data->getResult();
        return $result;
    }

    public function updateData(...$data)
    {
        parent::update($this->tableName, ...$data);
        $data = DbManager::getInstance()->query($this, true, 'default');
        $result = $data->getResult();
        return $result;
    }

    public function insertData(...$data)
    {
        parent::insert($this->tableName, ...$data);
        $data = DbManager::getInstance()->query($this, true, 'default');
        $insId = $data->getLastInsertId();
        return $insId;
    }

}

<?php

namespace EasySwoole\DDL\Blueprint\Drop;

/**
 * 删除没有关联的普通表
 * 有外键关联请先删除相应的外键
 * Class Table
 * @package EasySwoole\DDL\Blueprint\Drop
 */
class Table
{
    // 基础属性
    protected $table;
    protected $ifExists;

    /**
     * Table constructor.
     * @param string $tableName
     * @param bool $ifExists
     */
    public function __construct(string $tableName, bool $ifExists = false)
    {
        $this->setTableName($tableName);
        $this->setIfExists($ifExists);
    }

    /**
     * 设置表名称
     * @param string $name
     * @return Table
     */
    public function setTableName(string $name): Table
    {
        $name = trim($name);
        if (empty($name)) {
            throw new \InvalidArgumentException('The table name cannot be empty');
        }
        $this->table = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * 设置表如果存在删除
     * @param bool $ifExists
     * @return Table
     */
    public function setIfExists(bool $ifExists = true)
    {
        $this->ifExists = (bool)$ifExists;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIfExists()
    {
        return $this->ifExists;
    }


    // 生成表结构 带有下划线的方法请不要自行调用
    public function __createDDL()
    {
        return implode(' ',
                array_filter(
                    [
                        'DROP TABLE',
                        $this->getIfExists() ? 'IF EXISTS' : null,
                        '`' . $this->getTableName() . '`',
                    ]
                )
            ) . ';';
    }

    /**
     * 转化为字符串
     * @return string
     */
    public function __toString()
    {
        return $this->__createDDL();
    }
}
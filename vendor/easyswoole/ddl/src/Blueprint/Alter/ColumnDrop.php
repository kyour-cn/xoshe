<?php

namespace EasySwoole\DDL\Blueprint\Alter;

use EasySwoole\DDL\Enum\Alter;
use InvalidArgumentException;

/**
 * 修改表字段构造器
 * Class ColumnDrop
 * @package EasySwoole\DDL\Blueprint\Alter
 */
class ColumnDrop
{
    private $columnName;

    /**
     * Column constructor.
     * @param string $columnName 列的名称
     */
    public function __construct(string $columnName)
    {
        $this->setColumnName($columnName);
    }

    /**
     * 设置字段名称
     * @param string $name 字段名称
     * @return ColumnDrop
     */
    public function setColumnName(string $name): ColumnDrop
    {
        $name = trim($name);
        if (empty($name)) {
            throw new InvalidArgumentException('The column name cannot be empty');
        }
        $this->columnName = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * 创建DDL
     * 带下划线的方法请不要外部调用
     * @return string
     */
    public function __createDDL(): string
    {
        return implode(' ',
            array_filter(
                [
                    Alter::DROP,
                    '`' . $this->getColumnName() . '`',
                ]
            )
        );
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

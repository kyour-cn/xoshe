<?php

namespace EasySwoole\DDL\Blueprint\AbstractInterface;

/**
 * 字段构造器接口类
 * Class ColumnInterface
 * @package EasySwoole\DDL\Blueprint\AbstractInterface
 */
interface ColumnInterface
{
    /**
     * 设置字段名称
     * @param string $name 字段名称
     * @return ColumnInterface
     */
    public function setColumnName(string $name);

    /**
     * @return mixed
     */
    public function getColumnName();

    /**
     * 设置字段类型
     * @param string $type
     * @return ColumnInterface
     */
    public function setColumnType(string $type);

    /**
     * @return mixed
     */
    public function getColumnType();

    /**
     * 设置字段列宽限制
     * @param integer|array $limit
     * @return ColumnInterface
     */
    public function setColumnLimit($limit);

    /**
     * @return mixed
     */
    public function getColumnLimit();

    /**
     * 是否无符号
     * @param bool $enable
     * @return ColumnInterface
     */
    public function setIsUnsigned(bool $enable = true);

    /**
     * @return mixed
     */
    public function getUnsigned();

    /**
     * 是否零填充
     * @param bool $enable
     * @return ColumnInterface
     */
    public function setZeroFill(bool $enable = true);

    /**
     * @return mixed
     */
    public function getZeroFill();
}

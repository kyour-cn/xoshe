<?php

namespace EasySwoole\DDL\Blueprint;

use EasySwoole\DDL\Blueprint\Create\Table as CreateTable;

/**
 * 创建表结构描述
 * 暂只支持创建表 CREATE 结构
 * Class Table
 * @package EasySwoole\DDL\Blueprint
 */
class Table extends CreateTable
{
    public function colInt(string $name, int $limit = null)
    {
        return $this->int($name, $limit);
    }

    public function colBigInt(string $name, int $limit = null)
    {
        return $this->bigint($name, $limit);
    }

    public function colTinyInt(string $name, int $limit = null)
    {
        return $this->tinyint($name, $limit);
    }

    public function colSmallInt(string $name, int $limit = null)
    {
        return $this->smallint($name, $limit);
    }

    public function colMediumInt(string $name, int $limit = null)
    {
        return $this->mediumInt($name, $limit);
    }

    public function colFloat(string $name, int $precision = null, int $digits = null)
    {
        return $this->float($name, $precision, $digits);
    }

    public function colDouble(string $name, int $precision = null, int $digits = null)
    {
        return $this->double($name, $precision, $digits);
    }

    public function colDecimal(string $name, int $precision = 10, int $digits = 0)
    {
        return $this->decimal($name, $precision, $digits);
    }

    public function colDate(string $name)
    {
        return $this->date($name);
    }

    public function colYear(string $name)
    {
        return $this->year($name);
    }

    public function colTime(string $name, ?int $fsp = null)
    {
        return $this->time($name, $fsp);
    }

    public function colDateTime(string $name, ?int $fsp = null)
    {
        return $this->datetime($name, $fsp);
    }

    public function colTimestamp(string $name, ?int $fsp = null)
    {
        return $this->timestamp($name, $fsp);
    }

    public function colChar(string $name, ?int $limit = null)
    {
        return $this->char($name, $limit);
    }

    public function colVarChar(string $name, ?int $limit = null)
    {
        return $this->varchar($name, $limit);
    }

    public function colText(string $name)
    {
        return $this->text($name);
    }

    public function colTinyText(string $name)
    {
        return $this->tinytext($name);
    }

    public function colLongText(string $name)
    {
        return $this->longtext($name);
    }

    public function colMediumText(string $name)
    {
        return $this->mediumtext($name);
    }

    public function colBlob(string $name)
    {
        return $this->blob($name);
    }

    public function colLongBlob(string $name)
    {
        return $this->longblob($name);
    }

    public function colTinyBlob(string $name)
    {
        return $this->tinyblob($name);
    }

    public function colMediumBlob(string $name)
    {
        return $this->mediumblob($name);
    }

    public function indexNormal(string $name, $columns)
    {
        return $this->normal($name, $columns);
    }

    public function indexUnique(string $name, $columns)
    {
        return $this->unique($name, $columns);
    }

    public function indexPrimary(string $name, $columns)
    {
        return $this->primary($name, $columns);
    }

    public function indexFullText(string $name, $columns)
    {
        return $this->fulltext($name, $columns);
    }
}
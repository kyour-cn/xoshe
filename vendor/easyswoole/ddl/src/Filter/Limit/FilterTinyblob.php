<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 16:59
 */

namespace EasySwoole\DDL\Filter\Limit;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterTinyblob implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getColumnLimit()) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type tinyblob no require fsp ');
        }
    }
}
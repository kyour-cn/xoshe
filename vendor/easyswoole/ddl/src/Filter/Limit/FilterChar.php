<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 14:50
 */

namespace EasySwoole\DDL\Filter\Limit;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterChar implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getColumnLimit() < 0 || $column->getColumnLimit() > 255) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type char(limit), limit must be range 1 to 255');
        }
    }

}
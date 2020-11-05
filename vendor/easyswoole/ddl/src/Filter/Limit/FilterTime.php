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

class FilterTime implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getColumnLimit() < 0 || $column->getColumnLimit() > 6) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type time(fsp), fsp must be range 0 to 6');
        }
    }
}
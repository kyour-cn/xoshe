<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 14:49
 */

namespace EasySwoole\DDL\Filter\Limit;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterTimestamp implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getColumnLimit() < 0 || $column->getColumnLimit() > 6) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type timestamp(fsp), fsp must be range 0 to 6');
        }
    }
}
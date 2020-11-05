<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 16:55
 */

namespace EasySwoole\DDL\Filter\Limit;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterYear implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getColumnLimit()) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type year no require fsp ');
        }
    }
}
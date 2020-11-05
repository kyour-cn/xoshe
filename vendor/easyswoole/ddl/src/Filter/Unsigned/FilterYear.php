<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 16:55
 */

namespace EasySwoole\DDL\Filter\Unsigned;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterYear implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getUnsigned()) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type year no require unsigned ');
        }
    }
}
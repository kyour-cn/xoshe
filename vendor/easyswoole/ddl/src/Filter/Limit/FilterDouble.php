<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 14:48
 */

namespace EasySwoole\DDL\Filter\Limit;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterDouble implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if (is_array($column->getColumnLimit())) {
            list($precision, $digits) = $column->getColumnLimit();
            if (is_numeric($precision) && is_numeric($digits) && ($digits > $precision)) {
                throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type double(M,D), M must be >= D');
            }
        } elseif ($column->getColumnLimit() < 0) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type double(M), M must be >= 0');
        }

    }
}
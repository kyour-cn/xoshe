<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 16:58
 */

namespace EasySwoole\DDL\Filter\Zerofill;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterLongtext implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getZeroFill()) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type longtext no require zerofill ');
        }
    }
}
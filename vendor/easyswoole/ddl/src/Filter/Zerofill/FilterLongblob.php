<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 17:01
 */

namespace EasySwoole\DDL\Filter\Zerofill;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterLongblob implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        if ($column->getZeroFill()) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type longblob no require zerofill ');
        }
    }
}
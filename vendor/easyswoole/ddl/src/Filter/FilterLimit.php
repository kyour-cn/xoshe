<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/17
 * Time: 10:03
 */

namespace EasySwoole\DDL\Filter;


use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterLimit implements FilterInterface
{
    public static function run(ColumnInterface $column)
    {
        call_user_func(['EasySwoole\\DDL\\Filter\\Limit\\Filter' . ucfirst($column->getColumnType()), 'run'], $column);
    }
}
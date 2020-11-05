<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 17:24
 */

namespace EasySwoole\DDL\Contracts;

use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnInterface;

interface FilterInterface
{
    public static function run(ColumnInterface $column);
}
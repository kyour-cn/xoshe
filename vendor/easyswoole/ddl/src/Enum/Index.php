<?php

namespace EasySwoole\DDL\Enum;

use EasySwoole\Spl\SplEnum;

/**
 * 索引类型枚举
 * Class Index
 * @package EasySwoole\DDL\Enum
 */
class Index extends SplEnum
{
    const NORMAL = 'normal';
    const UNIQUE = 'unique';
    const PRIMARY = 'primary';
    const FULLTEXT = 'fulltext';
}
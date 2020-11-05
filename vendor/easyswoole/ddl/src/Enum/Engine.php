<?php

namespace EasySwoole\DDL\Enum;

use EasySwoole\Spl\SplEnum;

/**
 * 储存引擎类型枚举
 * Class Engine
 * @package EasySwoole\DDL\Enum
 */
class Engine extends SplEnum
{
    const CSV = 'csv';
    const INNODB = 'innodb';
    const MEMORY = 'memory';
    const MYISAM = 'myisam';
    const ARCHIVE = 'archive';
    const FEDERATED = 'federated';
    const BLACKHOLE = 'blackhole';
    const MRG_MYISAM = 'mrg_myisam';
    const PERFORMANCE_SCHEMA = 'performance_schema';
}
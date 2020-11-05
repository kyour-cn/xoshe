<?php

namespace EasySwoole\DDL\Enum;

/**
 * 外键删除、更新操作类型枚举
 * Class Foreign
 * @package EasySwoole\DDL\Enum
 */
class Foreign
{
    const CASCADE = 'CASCADE';
    const NO_ACTION = 'NO ACTION';
    const RESTRICT = 'RESTRICT';
    const SET_NULL = 'SET NULL';
}
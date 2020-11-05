<?php

namespace EasySwoole\DDL\Enum;

/**
 * 函数对象
 * Class Func
 * @package EasySwoole\DDL\Enum
 */
class Func
{
    protected $definition;

    /**
     * Func constructor.
     * @param $definition
     */
    function __construct($definition)
    {
        $this->definition = $definition;
    }

    /**
     * Definition Getter
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Definition Setter
     * @param mixed $definition
     */
    public function setDefinition($definition): void
    {
        $this->definition = $definition;
    }

    /**
     * 转为字符串
     * @return string
     */
    function __toString()
    {
        return (string)$this->definition;
    }
}
<?php

namespace EasySwoole\DDL\Blueprint\Alter;

use EasySwoole\DDL\Enum\Alter;
use \EasySwoole\DDL\Enum\Foreign as ForeignType;
use InvalidArgumentException;

/**
 * 外键构造器
 * Class ForeignDrop
 * @package EasySwoole\DDL\Blueprint\Alter
 */
class ForeignDrop
{
    protected $foreignName;         // 外键名称

    /**
     * Foreign constructor.
     * @param string $foreignName
     */
    public function __construct(string $foreignName)
    {
        $this->setForeignName($foreignName);
    }

    /**
     * 设置外键名称
     * @param string|null $foreignName
     * @return ForeignDrop
     */
    protected function setForeignName(?string $foreignName = null): ForeignDrop
    {
        $foreignName = trim($foreignName);
        if (empty($foreignName)) {
            throw new InvalidArgumentException('The foreign name cannot be empty');
        }
        $this->foreignName = is_string($foreignName) ? trim($foreignName) : null;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getForeignName()
    {
        return $this->foreignName;
    }

    /**
     * 生成索引DDL结构
     * 带有下划线的方法请不要自行调用
     * @return string
     */
    public function __createDDL()
    {
        return implode(' ',
            array_filter(
                [
                    Alter::DROP . ' FOREIGN KEY',
                    '`' . $this->getForeignName() . '`',
                ]
            )
        );
    }

    /**
     * 转化为字符串
     * @return string
     */
    public function __toString()
    {
        return $this->__createDDL();
    }
}
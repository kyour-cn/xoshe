<?php

namespace EasySwoole\DDL\Blueprint\Alter;

use EasySwoole\DDL\Enum\Alter;
use InvalidArgumentException;

/**
 * 索引构造器
 * Class IndexDrop
 * @package EasySwoole\DDL\Blueprint\Alter
 */
class IndexDrop
{
    protected $indexName;     // 索引名称

    /**
     * Index constructor.
     * @param string $indexName 不设置索引名可以传入NULL
     */
    public function __construct(string $indexName)
    {
        $this->setIndexName($indexName);
    }

    /**
     * 设置索引名称
     * @param string $name
     * @return IndexDrop
     */
    public function setIndexName(?string $name = null): IndexDrop
    {
        $name = trim($name);
        if (empty($name)) {
            throw new InvalidArgumentException('The index name cannot be empty');
        }
        $this->indexName = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndexName()
    {
        return $this->indexName;
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
                    Alter::DROP . ' INDEX',
                    '`' . $this->getIndexName() . '`',
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
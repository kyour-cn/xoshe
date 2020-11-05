<?php

namespace EasySwoole\DDL\Blueprint\AbstractInterface;

use EasySwoole\DDL\Enum\Index as IndexType;
use InvalidArgumentException;

/**
 * 索引构造器抽象类
 * Class IndexAbstract
 * @package EasySwoole\DDL\Blueprint\AbstractInterface
 */
abstract class IndexAbstract implements IndexInterface
{
    protected $indexName;     // 索引名称
    protected $indexType;     // 索引类型 NORMAL PRI UNI FULLTEXT
    protected $indexColumns;  // 被索引的列 字符串或数组(多个列)
    protected $indexComment;  // 索引注释

    /**
     * 设置索引名称
     * @param string $name
     * @return IndexAbstract
     */
    public function setIndexName(?string $name = null)
    {
        $name            = is_string($name) ? trim($name) : null;
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
     * 设置索引类型
     * @param string $type
     * @return IndexAbstract
     */
    public function setIndexType(string $type)
    {
        $type = trim($type);
        if (!IndexType::isValidValue($type)) {
            throw new InvalidArgumentException('The index type ' . $type . ' is invalid');
        }
        $this->indexType = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndexType()
    {
        return $this->indexType;
    }

    /**
     * 设置索引字段
     * @param string|array $columns 可以设置字符串和数组
     * @return IndexAbstract
     */
    public function setIndexColumns($columns)
    {
        $this->indexColumns = $columns;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndexColumns()
    {
        return $this->indexColumns;
    }

    /**
     * 设置索引备注
     * @param string $comment
     * @return IndexAbstract
     */
    public function setIndexComment(string $comment)
    {
        $this->indexComment = $comment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndexComment()
    {
        return $this->indexComment;
    }

    /**
     * 构造器最终生成方法
     * @return string
     */
    abstract public function __createDDL();

    /**
     * 转化为字符串
     * @return string
     */
    public function __toString()
    {
        return $this->__createDDL();
    }
}

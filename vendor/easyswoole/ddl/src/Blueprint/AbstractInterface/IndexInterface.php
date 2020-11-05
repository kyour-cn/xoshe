<?php

namespace EasySwoole\DDL\Blueprint\AbstractInterface;

/**
 * 索引构造器接口类
 * Class IndexInterface
 * @package EasySwoole\DDL\Blueprint\AbstractInterface
 */
interface IndexInterface
{
    /**
     * 设置索引名称
     * @param string $name
     * @return IndexInterface
     */
    public function setIndexName(?string $name = null);

    /**
     * @return mixed
     */
    public function getIndexName();

    /**
     * 设置索引类型
     * @param string $type
     * @return IndexInterface
     */
    public function setIndexType(string $type);

    /**
     * @return mixed
     */
    public function getIndexType();

    /**
     * 设置索引字段
     * @param string|array $columns 可以设置字符串和数组
     * @return IndexInterface
     */
    public function setIndexColumns($columns);

    /**
     * @return mixed
     */
    public function getIndexColumns();

    /**
     * 设置索引备注
     * @param string $comment
     * @return IndexInterface
     */
    public function setIndexComment(string $comment);

    /**
     * @return mixed
     */
    public function getIndexComment();
}

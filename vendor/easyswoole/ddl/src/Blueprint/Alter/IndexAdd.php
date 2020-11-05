<?php

namespace EasySwoole\DDL\Blueprint\Alter;

use EasySwoole\DDL\Blueprint\AbstractInterface\IndexAbstract;
use EasySwoole\DDL\Enum\Alter;
use EasySwoole\DDL\Enum\Index as IndexType;

/**
 * 索引构造器
 * Class IndexAdd
 * @package EasySwoole\DDL\Blueprint\Alter
 */
class IndexAdd extends IndexAbstract
{
    /**
     * 普通索引
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return IndexAdd
     */
    public function normal(string $name, $columns): IndexAdd
    {
        $this->setIndexName($name);
        $this->setIndexType(IndexType::NORMAL);
        $this->setIndexColumns($columns);
        return $this;
    }

    /**
     * 唯一索引
     * 请注意这属于约束的一种类型 不要和字段上的约束重复定义
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return IndexAdd
     */
    public function unique(string $name, $columns): IndexAdd
    {
        $this->setIndexName($name);
        $this->setIndexType(IndexType::UNIQUE);
        $this->setIndexColumns($columns);
        return $this;
    }

    /**
     * 主键索引
     * 请注意这属于约束的一种类型 不要和字段上的约束重复定义
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return IndexAdd
     */
    public function primary(string $name, $columns): IndexAdd
    {
        $this->setIndexName($name);
        $this->setIndexType(IndexType::PRIMARY);
        $this->setIndexColumns($columns);
        return $this;
    }

    /**
     * 全文索引
     * 请注意该类型的索引只能施加在TEXT类型的字段上
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return IndexAdd
     */
    public function fulltext(string $name, $columns): IndexAdd
    {
        $this->setIndexName($name);
        $this->setIndexType(IndexType::FULLTEXT);
        $this->setIndexColumns($columns);
        return $this;
    }

    /**
     * 组装索引字段名
     * @return string
     */
    private function parseIndexColumns()
    {
        $columnDDLs   = [];
        $indexColumns = $this->getIndexColumns();
        if (is_string($indexColumns)) {
            $indexColumns = [$indexColumns];
        }
        foreach ($indexColumns as $indexedColumn) {
            $columnDDLs[] = '`' . $indexedColumn . '`';
        }
        return '(' . implode(',', $columnDDLs) . ')';
    }

    /**
     * 生成索引DDL结构
     * 带有下划线的方法请不要自行调用
     * @return string
     */
    public function __createDDL()
    {
        $indexPrefix = [
            IndexType::NORMAL   => 'INDEX',
            IndexType::UNIQUE   => 'UNIQUE INDEX',
            IndexType::PRIMARY  => 'PRIMARY KEY',
            IndexType::FULLTEXT => 'FULLTEXT INDEX',
        ];
        return implode(' ',
            array_filter(
                [
                    Alter::ADD,
                    $indexPrefix[$this->getIndexType()],
                    $this->getIndexName() !== null ? '`' . $this->getIndexName() . '`' : null,
                    $this->parseIndexColumns(),
                    $this->getIndexComment() ? "COMMENT '" . addslashes($this->getIndexComment()) . "'" : null
                ]
            )
        );
    }
}
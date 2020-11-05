<?php

namespace EasySwoole\DDL\Blueprint\Create;

use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\DataType;
use EasySwoole\DDL\Enum\Engine as Engines;
use EasySwoole\DDL\Enum\Index as IndexType;

/**
 * 创建表结构描述
 * 暂只支持创建表 CREATE 结构
 * Class Table
 * @package EasySwoole\DDL\Blueprint\Create
 */
class Table
{
    // 基础属性
    protected $table;
    protected $comment;
    protected $engine = Engines::INNODB;
    protected $charset = Character::UTF8_GENERAL_CI;

    // 结构定义
    protected $columns = [];  // 表的行定义
    protected $indexes = [];  // 表的索引
    protected $foreignKeys = [];  // 表的外键

    // 额外选项
    protected $isTemporary = false;  // 是否临时表
    protected $ifNotExists = false;  // 是否不存在才创建
    protected $autoIncrement;        // 默认自增从该值开始

    /**
     * Table constructor.
     * @param $tableName
     */
    public function __construct($tableName)
    {
        $this->setTableName($tableName);
    }

    // 以下为字段构造方法

    /**
     * 整数 int
     * @param string $name 字段名称
     * @param null|integer $limit INT 4Bytes(2^31)
     * @return mixed
     */
    public function int(string $name, int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::INT);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 整数 BIGINT
     * @param string $name 字段名称
     * @param int|null $limit BIGINT 8Bytes(2^63)
     * @return Column
     */
    public function bigint(string $name, int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::BIGINT);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 整数 TINYINT
     * @param string $name 字段名称
     * @param int|null $limit TINYINT 1Bytes(2^7)
     * @return Column
     */
    public function tinyint(string $name, int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::TINYINT);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 整数 SMALLINT
     * @param string $name 字段名称
     * @param int|null $limit TINYINT 2Bytes(2^15)
     * @return Column
     */
    public function smallint(string $name, int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::SMALLINT);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 整数 MEDIUMINT
     * @param string $name 字段名称
     * @param int|null $limit MEDIUMINT 3Bytes(2^23)
     * @return Column
     */
    public function mediumInt(string $name, int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::MEDIUMINT);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 单精度浮点 - FLOAT
     * 注: 当设置总精度为24-53内部实际上是DOUBLE序列
     * @param string $name 字段名称
     * @param int|null $precision 字段总精度(允许储存多少位数|含小数点)
     * @param int|null $digits 小数点部分的精度(可空)
     * @return Column
     */
    public function float(string $name, int $precision = null, int $digits = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::FLOAT);
        if (is_numeric($precision) && is_numeric($digits)) {
            $this->columns[$name]->setColumnLimit([$precision, $digits]);
        } elseif (is_numeric($precision)) {
            $this->columns[$name]->setColumnLimit($precision);
        }
        return $this->columns[$name];
    }

    /**
     * 双精度浮点 - DOUBLE
     * @param string $name 字段名称
     * @param int|null $precision 字段总精度(允许储存多少位数|含小数点)
     * @param int|null $digits 小数点部分的精度(可空)
     * @return Column
     */
    public function double(string $name, int $precision = null, int $digits = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::DOUBLE);
        if (is_numeric($precision) && is_numeric($digits)) {
            $this->columns[$name]->setColumnLimit([$precision, $digits]);
        } elseif (is_numeric($precision)) {
            $this->columns[$name]->setColumnLimit($precision);
        }
        return $this->columns[$name];
    }

    /**
     * 定点小数 - DECIMAL
     * 注意当设置小数精度和总精度一致时整数部分只能为零
     * 注: 当未设置精度时MYSQL默认按 DECIMAL(10,0) 所以此处给出默认值
     * @param string $name 字段名称
     * @param int $precision 字段总精度(允许储存多少位数|含小数点)
     * @param int $digits 小数点部分的精度
     * @return Column
     */
    public function decimal(string $name, int $precision = 10, int $digits = 0): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::DECIMAL);
        $this->columns[$name]->setColumnLimit([$precision, $digits]);
        return $this->columns[$name];
    }

    /**
     * 日期时间 - DATE
     * @param string $name 字段名称
     * @return Column
     */
    public function date(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::DATE);
        return $this->columns[$name];
    }

    /**
     * 日期时间 - YEAR
     * @param string $name 字段名称
     * @return Column
     */
    public function year(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::YEAR);
        return $this->columns[$name];
    }

    /**
     * 日期时间 - TIME
     * @param string $name 字段名称
     * @param int|null $fsp 精度分数(详见MYSQL文档)
     * @return Column
     */
    public function time(string $name, ?int $fsp = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::TIME);
        if (is_numeric($fsp)) {
            $this->columns[$name]->setColumnLimit($fsp);
        }
        return $this->columns[$name];
    }

    /**
     * 日期时间 - DATETIME
     * @param string $name 字段名称
     * @param int|null $fsp 精度分数(详见MYSQL文档)
     * @return Column
     */
    public function datetime(string $name, ?int $fsp = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::DATETIME);
        if (is_numeric($fsp)) {
            $this->columns[$name]->setColumnLimit($fsp);
        }
        return $this->columns[$name];
    }

    /**
     * 日期时间 - TIMESTAMP
     * @param string $name 字段名称
     * @param int|null $fsp 精度分数(详见MYSQL文档)
     * @return Column
     */
    public function timestamp(string $name, ?int $fsp = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::TIMESTAMP);
        if (is_numeric($fsp)) {
            $this->columns[$name]->setColumnLimit($fsp);
        }
        return $this->columns[$name];
    }

    /**
     * 字符串 - CHAR
     * @param string $name 字段名称
     * @param int|null $limit
     * @return Column
     */
    public function char(string $name, ?int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::CHAR);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 字符串 - VARCHAR
     * @param string $name 字段名称
     * @param int|null $limit
     * @return Column
     */
    public function varchar(string $name, ?int $limit = null): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::VARCHAR);
        $this->columns[$name]->setColumnLimit($limit);
        return $this->columns[$name];
    }

    /**
     * 字符串 - TEXT
     * @param string $name 字段名称
     * @return Column
     */
    public function text(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::TEXT);
        return $this->columns[$name];
    }

    /**
     * 字符串 - TINYTEXT
     * @param string $name 字段名称
     * @return Column
     */
    public function tinytext(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::TINYTEXT);
        return $this->columns[$name];
    }

    /**
     * 字符串 - LONGTEXT
     * @param string $name 字段名称
     * @return Column
     */
    public function longtext(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::LONGTEXT);
        return $this->columns[$name];
    }

    /**
     * 字符串 - MEDIUMTEXT
     * @param string $name 字段名称
     * @return Column
     */
    public function mediumtext(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::MEDIUMTEXT);
        return $this->columns[$name];
    }

    /**
     * 二进制字符串 - BLOB
     * @param string $name 字段名称
     * @return Column
     */
    public function blob(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::BLOB);
        return $this->columns[$name];
    }

    /**
     * 二进制字符串 - LONGBLOB
     * @param string $name 字段名称
     * @return Column
     */
    public function longblob(string $name): Column
    {
        $this->columns[$name] = $this->createColumn($name, DataType::LONGBLOB);
        return $this->columns[$name];
    }

    /**
     * 二进制字符串 - TINYBLOB
     * @param string $name 字段名称
     * @return mixed
     */
    public function tinyblob(string $name)
    {
        $this->columns[$name] = $this->createColumn($name, DataType::TINYBLOB);
        return $this->columns[$name];
    }

    /**
     * 二进制字符串 - MEDIUMBLOB
     * @param string $name 字段名称
     * @return mixed
     */
    public function mediumblob(string $name)
    {
        $this->columns[$name] = $this->createColumn($name, DataType::MEDIUMBLOB);
        return $this->columns[$name];
    }


    // 以下为索引构造方法

    /**
     * 普通索引
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return Index
     */
    public function normal(string $name, $columns): Index
    {
        $this->indexes[$name] = $this->createIndex($name, IndexType::NORMAL, $columns);
        return $this->indexes[$name];
    }

    /**
     * 唯一索引
     * 请注意这属于约束的一种类型 不要和字段上的约束重复定义
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return Index
     */
    public function unique(string $name, $columns): Index
    {
        $this->indexes[$name] = $this->createIndex($name, IndexType::UNIQUE, $columns);
        return $this->indexes[$name];
    }

    /**
     * 主键索引
     * 请注意这属于约束的一种类型 不要和字段上的约束重复定义
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return Index
     */
    public function primary(string $name, $columns): Index
    {
        $this->indexes[$name] = $this->createIndex($name, IndexType::PRIMARY, $columns);
        return $this->indexes[$name];
    }

    /**
     * 全文索引
     * 请注意该类型的索引只能施加在TEXT类型的字段上
     * @param string|null $name 索引名称(不需要名称也可以传null)
     * @param string|array $columns 索引字段(多个字段可以传入数组)
     * @return Index
     */
    public function fulltext(string $name, $columns): Index
    {
        $this->indexes[$name] = $this->createIndex($name, IndexType::FULLTEXT, $columns);
        return $this->indexes[$name];
    }


    // 以下为外键构造方法

    /**
     * 外键约束
     * @param string|null $foreignName 外键名称(不需要名称也可以传null)
     * @param string|array $localColumn 从表字段
     * @param string $relatedTableName 主表表名
     * @param string|array $foreignColumn 主表字段
     * @return Foreign
     */
    public function foreign(?string $foreignName, $localColumn, string $relatedTableName, $foreignColumn)
    {
        $this->foreignKeys[$foreignName] = $this->createForeignKey($foreignName, $localColumn, $relatedTableName, $foreignColumn);
        return $this->foreignKeys[$foreignName];
    }



    // 以下为表本身属性的设置方法

    /**
     * 这是一个临时表
     * @param bool $enable
     * @return Table
     */
    public function setIsTemporary($enable = true): Table
    {
        $this->isTemporary = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsTemporary()
    {
        return $this->isTemporary;
    }

    /**
     * CREATE IF NOT EXISTS
     * @param bool $enable
     * @return Table
     */
    public function setIfNotExists($enable = true): Table
    {
        $this->ifNotExists = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIfNotExists()
    {
        return $this->ifNotExists;
    }

    /**
     * 设置表名称
     * @param string $name
     * @return Table
     */
    public function setTableName(string $name): Table
    {
        $name = trim($name);
        if (empty($name)) {
            throw new \InvalidArgumentException('The table name cannot be empty');
        }
        $this->table = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * 设置储存引擎
     * @param string $engine
     * @return Table
     */
    public function setTableEngine(string $engine): Table
    {
        $engine = trim($engine);
        if (!Engines::isValidValue($engine)) {
            throw new \InvalidArgumentException('The engine ' . $engine . ' is invalid');
        }
        $this->engine = $engine;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableEngine()
    {
        return $this->engine;
    }

    /**
     * 设置表注释
     * @param string $comment
     * @return Table
     */
    public function setTableComment(string $comment): Table
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableComment()
    {
        return $this->comment;
    }

    /**
     * 设置表字符集
     * @param string $charset
     * @return Table
     */
    public function setTableCharset(string $charset): Table
    {
        $charset = trim($charset);
        if (!Character::isValidValue($charset)) {
            throw new \InvalidArgumentException('The character ' . $charset . ' is invalid');
        }
        $this->charset = $charset;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableCharset()
    {
        return $this->charset;
    }

    /**
     * 设置起始自增值
     * @param int $startIncrement
     * @return Table
     */
    public function setTableAutoIncrement(int $startIncrement)
    {
        $this->autoIncrement = $startIncrement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * 创建一个列对象
     * 继承可以改变返回的类对象以使用自定义对象
     * @param string $columnName
     * @param string $columnType
     * @return Column
     */
    protected function createColumn(string $columnName, string $columnType)
    {
        return new Column($columnName, $columnType);
    }

    /**
     * 创建一个索引对象
     * 继承可以改变返回的类对象以使用自定义对象
     * @param string|null $indexName
     * @param $indexType
     * @param $indexColumns
     * @return Index
     */
    protected function createIndex(?string $indexName, $indexType, $indexColumns)
    {
        return new Index($indexName, $indexType, $indexColumns);
    }

    /**
     * 创建一个外键对象
     * 继承可以改变返回的类对象以使用自定义对象
     * @param string|null $foreignName
     * @param string|array $localColumn
     * @param string $relatedTableName
     * @param string|array $foreignColumn
     * @return Foreign
     */
    protected function createForeignKey(?string $foreignName, $localColumn, string $relatedTableName, $foreignColumn)
    {
        return new Foreign($foreignName, $localColumn, $relatedTableName, $foreignColumn);
    }

    // 生成表结构 带有下划线的方法请不要自行调用
    public function __createDDL()
    {
        // 表名称定义
        $tableName = "`{$this->getTableName()}`"; // 安全起见引号包裹

        // 表格字段定义
        $columnDefinitions = [];
        foreach ($this->columns as $name => $column) {
            $columnDefinitions[] = '  ' . (string)$column;
        }

        // 表格索引定义
        $indexDefinitions = [];
        foreach ($this->indexes as $name => $index) {
            $indexDefinitions[] = '  ' . (string)$index;
        }

        // 表格外键定义
        $foreignDefinitions = [];
        foreach ($this->foreignKeys as $name => $index) {
            $foreignDefinitions[] = '  ' . (string)$index;
        }

        // 表格属性定义
        $tableOptions = array_filter(
            [
                $this->getTableEngine() ? 'ENGINE = ' . strtoupper($this->getTableEngine()) : null,
                $this->getTableAutoIncrement() ? "AUTO_INCREMENT = " . intval($this->getTableAutoIncrement()) : null,
                $this->getTableCharset() ? "DEFAULT COLLATE = '" . $this->getTableCharset() . "'" : null,
                $this->getTableComment() ? "COMMENT = '" . addslashes($this->getTableComment()) . "'" : null
            ]
        );
        $ifNotExists  = $this->getIfNotExists() ? 'IF NOT EXISTS' : '';
        // 构建表格DDL
        $createDDL = implode(
                "\n",
                array_filter(
                    [
                        "CREATE TABLE {$ifNotExists} {$tableName} (",
                        implode(",\n",
                            array_merge(
                                $columnDefinitions,
                                $indexDefinitions,
                                $foreignDefinitions
                            )
                        ),
                        ')',
                        implode(" ", $tableOptions),
                    ]
                )
            ) . ';';

        return $createDDL;
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
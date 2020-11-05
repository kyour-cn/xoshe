<?php

namespace EasySwoole\DDL\Blueprint\Alter;

use EasySwoole\DDL\Blueprint\AbstractInterface\ColumnAbstract;
use EasySwoole\DDL\Enum\Alter;
use EasySwoole\DDL\Enum\DataType;
use EasySwoole\DDL\Filter\FilterLimit;
use EasySwoole\DDL\Filter\FilterUnsigned;
use EasySwoole\DDL\Filter\FilterZerofill;

/**
 * 修改表字段构造器
 * Class ColumnAdd
 * @package EasySwoole\DDL\Blueprint\Alter
 */
class ColumnAdd extends ColumnAbstract
{
    /**
     * 整数 int
     * @param string $name 字段名称
     * @param int|null $limit INT 4Bytes(2^31)
     * @return ColumnAdd
     */
    public function int(string $name, int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::INT)->setColumnLimit($limit);
    }

    /**
     * 整数 BIGINT
     * @param string $name 字段名称
     * @param int|null $limit BIGINT 8Bytes(2^63)
     * @return ColumnAdd
     */
    public function bigint(string $name, int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::BIGINT)->setColumnLimit($limit);
    }

    /**
     * 整数 TINYINT
     * @param string $name 字段名称
     * @param int|null $limit TINYINT 1Bytes(2^7)
     * @return ColumnAdd
     */
    public function tinyint(string $name, int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::TINYINT)->setColumnLimit($limit);
    }

    /**
     * 整数 SMALLINT
     * @param string $name 字段名称
     * @param int|null $limit TINYINT 2Bytes(2^15)
     * @return ColumnAdd
     */
    public function smallint(string $name, int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::SMALLINT)->setColumnLimit($limit);
    }

    /**
     * 整数 MEDIUMINT
     * @param string $name 字段名称
     * @param int|null $limit MEDIUMINT 3Bytes(2^23)
     * @return ColumnAdd
     */
    public function mediumInt(string $name, int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::MEDIUMINT)->setColumnLimit($limit);
    }

    /**
     * 单精度浮点 - FLOAT
     * 注: 当设置总精度为24-53内部实际上是DOUBLE序列
     * @param string $name 字段名称
     * @param int|null $precision 字段总精度(允许储存多少位数|含小数点)
     * @param int|null $digits 小数点部分的精度(可空)
     * @return ColumnAdd
     */
    public function float(string $name, int $precision = null, int $digits = null): ColumnAdd
    {
        $this->setColumnName($name)->setColumnType(DataType::FLOAT);
        if (is_numeric($precision) && is_numeric($digits)) {
            $this->setColumnLimit([$precision, $digits]);
        } elseif (is_numeric($precision)) {
            $this->setColumnLimit($precision);
        }
        return $this;
    }

    /**
     * 双精度浮点 - DOUBLE
     * @param string $name 字段名称
     * @param int|null $precision 字段总精度(允许储存多少位数|含小数点)
     * @param int|null $digits 小数点部分的精度(可空)
     * @return ColumnAdd
     */
    public function double(string $name, int $precision = null, int $digits = null): ColumnAdd
    {
        $this->setColumnName($name)->setColumnType(DataType::DOUBLE);
        if (is_numeric($precision) && is_numeric($digits)) {
            $this->setColumnLimit([$precision, $digits]);
        } elseif (is_numeric($precision)) {
            $this->setColumnLimit($precision);
        }
        return $this;
    }

    /**
     * 定点小数 - DECIMAL
     * 注意当设置小数精度和总精度一致时整数部分只能为零
     * 注: 当未设置精度时MYSQL默认按 DECIMAL(10,0) 所以此处给出默认值
     * @param string $name 字段名称
     * @param int $precision 字段总精度(允许储存多少位数|含小数点)
     * @param int $digits 小数点部分的精度
     * @return ColumnAdd
     */
    public function decimal(string $name, int $precision = 10, int $digits = 0): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::DECIMAL)->setColumnLimit([$precision, $digits]);
    }

    /**
     * 日期时间 - DATE
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function date(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::DATE);
    }

    /**
     * 日期时间 - YEAR
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function year(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::YEAR);
    }

    /**
     * 日期时间 - TIME
     * @param string $name 字段名称
     * @param int|null $fsp 精度分数(详见MYSQL文档)
     * @return ColumnAdd
     */
    public function time(string $name, int $fsp = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::TIME)->setColumnLimit($fsp);
    }

    /**
     * 日期时间 - DATETIME
     * @param string $name 字段名称
     * @param int|null $fsp 精度分数(详见MYSQL文档)
     * @return ColumnAdd
     */
    public function datetime(string $name, int $fsp = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::DATETIME)->setColumnLimit($fsp);
    }

    /**
     * 日期时间 - TIMESTAMP
     * @param string $name 字段名称
     * @param int|null $fsp 精度分数(详见MYSQL文档)
     * @return ColumnAdd
     */
    public function timestamp(string $name, ?int $fsp = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::TIMESTAMP)->setColumnLimit($fsp);
    }

    /**
     * 字符串 - CHAR
     * @param string $name 字段名称
     * @param int|null $limit
     * @return ColumnAdd
     */
    public function char(string $name, ?int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::CHAR)->setColumnLimit($limit);
    }

    /**
     * 字符串 - VARCHAR
     * @param string $name 字段名称
     * @param int|null $limit
     * @return ColumnAdd
     */
    public function varchar(string $name, int $limit = null): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::VARCHAR)->setColumnLimit($limit);
    }

    /**
     * 字符串 - TEXT
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function text(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::TEXT);
    }

    /**
     * 字符串 - TINYTEXT
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function tinytext(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::TINYTEXT);
    }

    /**
     * 字符串 - LONGTEXT
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function longtext(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::LONGTEXT);
    }

    /**
     * 字符串 - MEDIUMTEXT
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function mediumtext(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::MEDIUMTEXT);
    }

    /**
     * 二进制字符串 - BLOB
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function blob(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::BLOB);
    }

    /**
     * 二进制字符串 - LONGBLOB
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function longblob(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::LONGTEXT);
    }

    /**
     * 二进制字符串 - TINYBLOB
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function tinyblob(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::TINYBLOB);
    }

    /**
     * 二进制字符串 - MEDIUMBLOB
     * @param string $name 字段名称
     * @return ColumnAdd
     */
    public function mediumblob(string $name): ColumnAdd
    {
        return $this->setColumnName($name)->setColumnType(DataType::MEDIUMBLOB);
    }

    /**
     * 处理字段的默认值
     * @return bool|string
     */
    private function parseDefaultValue()
    {
        // AUTO_INCREMENT 和默认值不能同时出现
        if ($this->autoIncrement) {
            return false;
        }
        // 如果当前允许NULL值 而没有设置默认值 那么默认就为NULL
        if (!$this->isNotNull && ($this->defaultValue == null || $this->defaultValue == 'NULL')) {
            return 'NULL';
        }

        // 否则字段是不允许NULL值的 如果默认值是文本应该加入引号
        if (is_string($this->defaultValue)) {
            return "'" . $this->defaultValue . "'";
        } else if (is_bool($this->defaultValue)) {  // 布尔类型强转0和1
            return $this->defaultValue ? '1' : '0';
        } else if (is_null($this->defaultValue)) {
            return false;
        } else {  // 其他类型强转String
            return (string)$this->defaultValue;
        }
    }

    /**
     * 处理字段的类型宽度限制
     * @return bool|string
     */
    private function parseColumnLimit()
    {
        // 是一个数组需要用逗号接起来
        if (is_array($this->columnLimit)) {
            return "(" . implode(',', $this->columnLimit) . ")";
        }
        // 是一个数字可以直接设置在类型后面
        if (is_numeric($this->columnLimit)) {
            return "(" . $this->columnLimit . ")";
        }
        // 否则没有设置
        return false;
    }

    /**
     * 处理数据类型
     * @return string
     */
    private function parseDataType()
    {
        $columnLimit = $this->parseColumnLimit();
        $columnType  = $this->columnType;
        if ($columnLimit) {
            $columnType .= $columnLimit;
        }
        return $columnType;
    }


    /**
     * 创建DDL
     * 带下划线的方法请不要外部调用
     * @return string
     */
    public function __createDDL(): string
    {
        FilterLimit::run($this);//检测limit是否合法
        FilterUnsigned::run($this); //检测无符号类型
        FilterZerofill::run($this); //检测是否补充长度
        $default       = $this->parseDefaultValue();
        $columnCharset = $this->columnCharset ? explode('_', $this->columnCharset)[0] : false;
        return implode(' ',
            array_filter(
                [
                    Alter::ADD,
                    '`' . $this->columnName . '`',
                    (string)$this->parseDataType(),
                    $this->isBinary ? 'BINARY' : null,
                    $this->columnCharset ? 'CHARACTER SET ' . strtoupper($columnCharset) . ' COLLATE ' . strtoupper($this->columnCharset) : null,
                    $this->unsigned ? 'UNSIGNED' : null,
                    $this->zeroFill ? 'ZEROFILL' : null,
                    $this->isUnique ? 'UNIQUE' : null,
                    $this->isNotNull ? 'NOT NULL' : 'NULL',
                    $default !== false ? 'DEFAULT ' . $default : null,
                    $this->isPrimaryKey ? 'PRIMARY KEY' : null,
                    $this->autoIncrement ? 'AUTO_INCREMENT' : null,
                    $this->columnComment ? sprintf("COMMENT '%s'", addslashes($this->columnComment)) : null
                ]
            )
        );
    }
}

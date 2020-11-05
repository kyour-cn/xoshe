<?php

namespace EasySwoole\DDL\Blueprint\Alter;

/**
 * 外键构造器
 * Class ForeignModify
 * @package EasySwoole\DDL\Blueprint\Alter
 */
class ForeignModify
{
    /** @var ForeignAdd */
    protected $foreignAddObj;

    /**
     * Foreign constructor.
     * @param string|null $foreignName
     * @param string|array $localColumn
     * @param string $relatedTableName
     * @param string|array $foreignColumn
     * @return ForeignAdd
     */
    public function foreign(?string $foreignName, $localColumn, string $relatedTableName, $foreignColumn)
    {
        $this->foreignAddObj = new ForeignAdd($foreignName, $localColumn, $relatedTableName, $foreignColumn);
        return $this->foreignAddObj;
    }

    /**
     * 主表删除操作从表动作
     * \EasySwoole\DDL\Enum\Foreign
     * @param string $option
     * @return ForeignModify
     */
    public function setOnDelete(string $option): ForeignModify
    {
        $this->foreignAddObj->setOnDelete($option);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOnDelete()
    {
        return $this->foreignAddObj->getOnDelete();
    }

    /**
     * 主表更新操作从表动作
     * \EasySwoole\DDL\Enum\Foreign
     * @param string $option
     * @return ForeignModify
     */
    public function setOnUpdate(string $option): ForeignModify
    {
        $this->foreignAddObj->setOnUpdate($option);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOnUpdate()
    {
        return $this->foreignAddObj->getOnUpdate();
    }

    /**
     * 生成索引DDL结构
     * 带有下划线的方法请不要自行调用
     * @return string
     */
    public function __createDDL()
    {
        return $this->foreignAddObj->__createDDL();
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
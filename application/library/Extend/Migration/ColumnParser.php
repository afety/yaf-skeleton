<?php

namespace Library\Extend\Migration;

use Doctrine\DBAL\Schema\Column;

class ColumnParser
{
    private const SETTER_PREFIX = 'set';
    private const GETTER_PREFIX = 'get';
    protected static $fieldComment = "'<columnName>' => '<comment>',";
    protected static $setterTemplate =
        '
    /**
     * @param <variableType> $<variableName>
     * @return $this
     */
    public function <funcname>(<variableType> $<variableName>)
    {
        $this-><columnName> = $<variableName>;
        return $this;
    }';
    protected static $getterTemplate =
        '
    /**
     * @return <variableType>
     */
    public function <funcname>()
    {
        return $this-><columnName>;
    }';
    private $column = null;
    private $columnName = '';
    private $variableType = '';
    private $variableName = '';
    private $upperName = '';
    private $setterFuncName = '';
    private $getterFuncName = '';
    private $comment = '';

    /**
     * ColumnParser constructor.
     * @param Column $column
     */
    public function __construct(Column $column)
    {
        $this->column = $column;

        $this->columnName = $column->getName();
        $this->comment = $column->getComment();
        $this->variableType = ($column->getNotnull() ? '' : '?' ).TypeMap::getPHPType($column->getType()->getName());
        $this->variableName = snakeToCamelCase($this->columnName);
        $this->upperName = ucfirst($this->variableName);
        $this->setterFuncName = self::SETTER_PREFIX . $this->upperName;
        $this->getterFuncName = self::GETTER_PREFIX . $this->upperName;
    }

    /**
     *
     *
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     *
     *
     * @return bool|string
     */
    public function getVariableType()
    {
        return $this->variableType;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getVariableName()
    {
        return $this->getVariableName();
    }

    /**
     *
     *
     * @return string
     */
    public function getUpperName()
    {
        return $this->upperName;
    }

    /**
     *
     *
     * @return string
     */
    public function getSetterFuncName()
    {
        return $this->setterFuncName;
    }

    /**
     *
     *
     * @return string
     */
    public function getGetterFuncName()
    {
        return $this->getterFuncName;
    }

    /**
     *
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     *
     *
     * @return mixed
     */
    public function generateSetterFuncStr()
    {
        return str_replace(
            [
                '<funcname>',
                '<variableType>',
                '<variableName>',
                '<columnName>',
            ],
            [
                $this->setterFuncName,
                $this->variableType,
                $this->variableName,
                $this->columnName
            ],
            self::$setterTemplate);
    }

    /**
     *
     *
     * @return mixed
     */
    public function generateGetterFuncStr()
    {
        return str_replace(
            [
                '<funcname>',
                '<columnName>',
                '<variableType>',
            ],
            [
                $this->getterFuncName,
                $this->columnName,
                $this->variableType,
            ],
            self::$getterTemplate);
    }

    /**
     *
     *
     * @return mixed
     */
    public function getFieldCommentStr()
    {
        return str_replace(
            [
                '<columnName>',
                '<comment>',
            ],
            [
                $this->columnName,
                $this->comment,
            ],
            self::$fieldComment
        );
    }
}
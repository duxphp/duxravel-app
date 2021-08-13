<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Widget
 * @package Duxravel\Core\UI\Widget
 */
class Widget
{
    protected $callback;

    protected array $class = [];
    protected array $restClass = [];
    protected array $attr = [];
    protected array $style = [];
    protected $callbackData;

    /**
     * 属性数据
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function attr(string $name, string $value): self
    {
        $this->attr[$name] = $value;
        return $this;
    }

    /**
     * class样式
     * @param string $name
     * @return $this
     */
    public function class(string $name): self
    {
        $this->class[] = $name;
        return $this;
    }

    /**
     * 重置class样式
     * @param string $name
     * @return $this
     */
    public function restClass(string $name): self
    {
        $this->restClass[] = $name;
        return $this;
    }

    /**
     * 设置样式
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function style(string $name, string $value): self
    {
        $this->style[$name] = $value;
        return $this;
    }

    /**
     * 设置变量
     * @param $name
     * @param $value
     * @return $this
     */
    public function setValue($name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    /**
     * 获取变量
     * @param $name
     * @return mixed
     */
    public function getValue($name)
    {
        return $this->$name;
    }

    /**
     * 合并数组
     * @param array $array
     * @param string $str
     * @return string
     */
    public function mergeArray(array $array, string $str = ''): string
    {
        return implode($str, $array);
    }

    /**
     * 转换元素属性
     * @return string
     */
    public function toElement(): string
    {
        return implode(' ', [$this->toClass(), $this->toAttr(), $this->toStyle()]);
    }

    /**
     * 转换class
     * @return string
     */
    public function toClass(): string
    {
        return Tools::toClass($this->restClass ?: $this->class);
    }

    /**
     * 转换属性
     * @return string
     */
    public function toAttr(): string
    {
        return Tools::toAttr($this->attr);
    }

    /**
     * 转换样式
     * @return string
     */
    public function toStyle(): string
    {
        return Tools::toStyle($this->style);
    }

    /**
     * 回调设置
     * @return $this
     */
    public function next(): Widget
    {
        if (!$this->callback) {
            return $this;
        }
        $this->callbackData = call_user_func($this->callback, $this);
        return $this;
    }

    /**
     * @return array
     */
    public function getRender()
    {
        return array_merge($this->render(), $this->attr);
    }



}

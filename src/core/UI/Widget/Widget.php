<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Widget
 * @package Duxravel\Core\UI\Widget
 */
class Widget
{
    protected ?\Closure $callback;

    protected array $class = [];
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
     * @param array  $array
     * @param string $str
     * @return string
     */
    public function mergeArray(array $array, string $str = ''): string
    {
        return implode($str, $array);
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
    public function getRender(): array
    {
        return array_merge($this->render(), $this->attr);
    }


}

<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Number
 * 数字输入器
 * @package Duxravel\Core\UI\Form
 */
class Number extends Element implements Component
{
    protected Text $object;

    /**
     * Text constructor.
     * @param  string  $name
     * @param  string  $field
     * @param  string  $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->object = new Text($this->name, $this->field, $this->has);
        $this->object->type('number');
    }

    /**
     * 最大值
     * @param int $default
     * @return $this
     */
    public function max(int $default = 0): self
    {
        $this->object->attr('max', $default);
        return $this;
    }

    /**
     * 最小值
     * @param int $default
     * @return $this
     */
    public function min(int $default = 0): self
    {
        $this->object->attr('min', $default);
        return $this;
    }

    /**
     * 步进小数
     * @param int $default
     * @return $this
     */
    public function step(int $default = 2): self
    {
        $this->object->attr('step', $default);
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        return $this->object->render($this->getValue($value));
    }

}

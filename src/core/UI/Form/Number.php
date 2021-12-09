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
    protected int $step = 1;
    protected ?int $max = null;
    protected ?int $min = 0;
    protected ?int $precision = null;

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
    }

    /**
     * 最大值
     * @param int $default
     * @return $this
     */
    public function max(int $default = 0): self
    {
        $this->max = $default;
        return $this;
    }

    /**
     * 最小值
     * @param int $default
     * @return $this
     */
    public function min(int $default = 0): self
    {
        $this->min = $default;
        return $this;
    }

    /**
     * 步进小数
     * @param int $default
     * @return $this
     */
    public function step(int $default = 1, ?int $precision = null): self
    {
        $this->step = $default;
        $this->precision = $precision;
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'a-input-number',
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'vModel:modelValue' => $this->getModelField(),
            'step' => $this->step,
            'min' => $this->min,
            'mode' => 'button'
        ];
        if ($this->max) {
            $data['max'] = $this->max;
        }
        if ($this->precision) {
            $data['precision'] = $this->precision;
        }
        return $data;
    }

}

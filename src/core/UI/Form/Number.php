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
    public function step(int $default = 1): self
    {
        $this->step = $default;
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
            'nodeName' => 'n-input-number',
            'class' => 'shadow-sm',
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'step' => $this->step,
            'min' => $this->min,
            'max' => $this->max,
        ];
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

}

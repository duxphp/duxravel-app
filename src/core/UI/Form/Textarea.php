<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Textarea
 * 多行输入框
 * @package Duxravel\Core\UI\Form
 */
class Textarea extends Element implements Component
{
    protected int $limit = 0;

    /**
     * Text constructor.
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
    }

    /**
     * @param $num
     * @return $this
     */
    public function limit($num): self
    {
        $this->limit = $num;
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
            'nodeName' => 'a-textarea',
            'vModel:modelValue' => $this->getModelField(),
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'allowClear' => true,
            'showWordLimit' => true
        ];
        if ($this->limit) {
            $data['maxLength'] = $this->limit;
        }
        return $data;
    }

}

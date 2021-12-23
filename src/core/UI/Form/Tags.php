<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Tags
 * 标签输入框
 * @package Duxravel\Core\UI\Form
 */
class Tags extends Element implements Component
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
            'nodeName' => 'a-input-tag',
            'vModel:modelValue' => $this->getModelField(),
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'allowClear' => true,
        ];
        if ($this->limit) {
            $data['maxTagCount'] = $this->limit;
        }
        return $data;
    }

    public function dataValue($value)
    {
        return array_values(array_filter((array) $this->getValueArray($value)));
    }

    public function dataInput($data)
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

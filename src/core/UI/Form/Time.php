<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Time
 * 时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Time extends Element implements Component
{
    protected string $string = 'HH:mm:ss';

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
     * 时间格式
     * @param string $format
     * @return $this
     */
    public function string(string $format): self
    {
        $this->string = $format;
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'a-time-picker',
            'allowClear' => true,
            'format' => $this->string,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            'vModel:model-value' => $this->getModelField()
        ];
        return $data;
    }

}

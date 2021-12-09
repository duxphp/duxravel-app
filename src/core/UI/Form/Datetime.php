<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Datetime
 * 日期时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Datetime extends Element implements Component
{

    protected string $string = 'YYYY-MM-DD hh:mm';

    /**
     * Datetime constructor.
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
     * 日期格式
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
     * @param $value
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'a-date-picker',
            'vModel:value' => $this->getModelField(),
            'showTime' => true,
            'allowClear' => true,
            'format' => $this->string,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            'vModel:modelValue' => $this->getModelField(),
        ];

        return $data;
    }

    /**
     * 获取输入值
     * @param $data
     */
    public function dataInput($data)
    {
        return $data ? strtotime($data) : null;
    }

    public function dataValue($data)
    {
        return $data ? date('Y-m-d H:i:s', $data) : null;
    }

}

<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Datetime
 * 日期时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Datetime extends Element implements Component
{

    protected string $string = 'YYYY-MM-DD HH:mm';

    /**
     * Datetime constructor.
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
     * @return array
     */
    public function render(): array
    {
        return [
            'nodeName' => 'a-date-picker',
            'vModel:value' => $this->getModelField(),
            'showTime' => true,
            'allowClear' => true,
            'format' => $this->string,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            'vModel:modelValue' => $this->getModelField(),
        ];
    }

    /**
     * 获取输入值
     * @param $data
     * @return false|int|null
     */
    public function dataInput($data)
    {
        return $data ? date('Y-m-d H:i:s', strtotime($data)) : null;
    }

    public function dataValue($data)
    {
        $data = $this->getValue($data);
        return $data ?: null;
    }

}

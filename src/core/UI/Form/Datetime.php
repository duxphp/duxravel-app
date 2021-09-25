<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Datetime
 * 日期时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Datetime extends Element implements Component
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
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'n-date-picker',
            'vModel:value' => $this->getModelField(),
            'class' => 'shadow-sm',
            'type' => 'datetime',
            'clearable' => true,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
        ];

        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }

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

}

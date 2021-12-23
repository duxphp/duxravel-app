<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Date
 * 时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Date extends Element implements Component
{

    protected ?string $type = null;

    private $types = [
        'year',
        'month',
        'quarter',
        'week'
    ];

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
     * 类型选择
     * @param string $type
     * @return $this
     * @throws \Exception
     */
    public function type(string $type): self
    {
        if (in_array($type, $this->types)) {
            throw new \Exception('There is no type "' . $type . '"');
        }
        $this->type = $type;
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
            'allowClear' => true,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            'vModel:modelValue' => $this->getModelField(),
        ];
        if ($this->type) {
            $data['nodeName'] = 'a-' . $this->type . '-picker';
        }
        return $data;

    }

    /**
     * 获取输入值
     * @param $data
     * @return string|null
     */
    public function dataInput($data)
    {
        return $data ? strtotime($data) : null;
    }

    public function dataValue($data)
    {
        $data = $this->getValue($data);
        return $data ? date('Y-m-d', $data) : null;
    }

}

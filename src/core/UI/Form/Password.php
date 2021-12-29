<?php

namespace Duxravel\Core\UI\Form;

/**
 * 密码输入器
 * @package Duxravel\Core\UI\Form
 */
class Password extends Element implements Component
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
     * @return array
     */
    public function render(): array
    {
        return [
            'nodeName' => 'a-input-password',
            'vModel:modelValue' => $this->getModelField(),
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'allowClear' => true,
        ];
    }


}

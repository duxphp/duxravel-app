<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Password
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
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'a-input-password',
            'vModel:modelValue' => $this->getModelField(),
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'allowClear' => true,
        ];
        return $data;
    }


}

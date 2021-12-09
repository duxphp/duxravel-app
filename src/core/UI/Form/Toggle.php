<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Toggle
 * 开关切换
 * @package Duxravel\Core\UI\Form
 */
class Toggle extends Element implements Component
{


    /**
     * Toggle constructor.
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
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'a-switch',
            'vModel:modelValue' => $this->getModelField(),
        ];
        return $data;
    }

    public function dataInput($data): int
    {
        return $data ? 1 : 0;
    }

}

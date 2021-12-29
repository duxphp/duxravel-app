<?php

namespace Duxravel\Core\UI\Form;

/**
 * 开关切换
 * @package Duxravel\Core\UI\Form
 */
class Toggle extends Element implements Component
{


    /**
     * Toggle constructor.
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
     * @return array
     */
    public function render(): array
    {
        return [
            'nodeName' => 'a-switch',
            'vModel:modelValue' => $this->getModelField(),
        ];
    }

    /**
     * @param $data
     * @return int
     */
    public function dataInput($data): int
    {
        return $data ? 1 : 0;
    }

}

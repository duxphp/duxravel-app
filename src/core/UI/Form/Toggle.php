<?php

namespace Duxravel\Core\UI\Form;

/**
 * 开关切换
 * @package Duxravel\Core\UI\Form
 */
class Toggle extends Element implements Component
{
    private $checkedValue = 1;
    private $uncheckedValue = 0;

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
            'checkedValue' => $this->checkedValue,
            'uncheckedValue' => $this->uncheckedValue
        ];
    }

    /**
     * 开关数据
     * @param string|number|boolean $checkedValue
     * @param string|number|boolean $uncheckedValue
     * @return $this
     */
    public function data($checkedValue,$uncheckedValue){
        $this->checkedValue = $checkedValue;
        $this->uncheckedValue = $uncheckedValue;
        return $this;
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

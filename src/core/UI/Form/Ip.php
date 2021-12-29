<?php

namespace Duxravel\Core\UI\Form;

/**
 * IP地址
 * @package Duxravel\Core\UI\Form
 */
class Ip extends Element implements Component
{
    protected Text $object;

    /**
     * @param  string  $name
     * @param  string  $field
     * @param  string  $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->object = new Text($this->name, $this->field, $this->has);
        $this->object->afterIcon('desktop');
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return $this->object->getRender();
    }

}

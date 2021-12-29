<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Tel
 * @package Duxravel\Core\UI\Form
 */
class Tel extends Element implements Component
{
    protected Text $object;
    protected string $mask = '19999999999';

    /**
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->object = new Text($this->name, $this->field, $this->has);
        $this->object->afterIcon('phone');
    }

    /**
     * 设置掩码
     * @param $value
     * @return $this
     */
    public function mask($value): self
    {
        $this->mask = (string) $value;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $this->object->attrArray($this->attr);
        return $this->object->getRender();
    }

}

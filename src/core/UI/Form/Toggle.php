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
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value);

        $this->class('form-toggle');
        $this->attr('name', $this->field);

        $checked = $value ? 'checked' : '';
        return <<<HTML
            <label {$this->toClass()}>
                <input class="form-toggle-input" $checked type="checkbox"  value="1" {$this->toStyle()} {$this->toAttr()}>
                <span class="form-toggle-label"></span>
            </label>
        HTML;
    }

    public function getInputData($data): int
    {
        return $data ? 1 : 0;
    }

}

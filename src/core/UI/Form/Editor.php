<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Editor
 * @package Duxravel\Core\UI\Form
 */
class Editor extends Element implements Component
{
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
    public function render($value)
    {
        $data = [
            'nodeName' => 'app-editor'
        ];

        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

}

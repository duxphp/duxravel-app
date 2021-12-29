<?php

namespace Duxravel\Core\UI\Form;

/**
 * 编辑器
 * @package Duxravel\Core\UI\Form
 */
class Editor extends Element implements Component
{
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
    }

    /**
     * @return array
     */
    public function render(): array
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

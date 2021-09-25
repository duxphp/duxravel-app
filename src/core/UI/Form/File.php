<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;

/**
 * Class File
 * @package Duxravel\Core\UI\Form
 */
class File extends Element implements Component
{
    protected string $type = 'upload';
    protected string $url = '';

    /**
     * File constructor.
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

    public function type($type = 'upload')
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'app-file',
        ];
        if ($this->url) {
            $data['upload'] = $this->url;
        }
        if ($this->type) {
            $data['type'] = $this->type;
        }
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

}

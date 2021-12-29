<?php

namespace Duxravel\Core\UI\Form;

/**
 * 颜色选择器
 * @package Duxravel\Core\UI\Form
 */
class Color extends Element implements Component
{
    protected array $color = [];
    protected bool $picker = false;

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
     * @return $this
     */
    public function picker(): self
    {
        $this->picker = true;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function color(array $data): self
    {
        $this->color = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        if ($this->picker) {
            // 暂无组件
            $data = [

            ];
        }else {
            $data = [
                'nodeName' => 'app-color',
                'colors' => $this->color,
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            ];
        }
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }

        return $data;
    }

}

<?php

namespace Duxravel\Core\UI\Form;

/**
 * 颜色选择器
 * Class Color
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
     * 自定义选择器
     * @return $this
     */
    public function picker(): self
    {
        $this->picker = true;
        return $this;
    }

    /**
     * 预设颜色
     * @param $data
     * @return $this
     */
    public function color($data): self
    {
        $this->color = $data;
        return $this;
    }


    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        if ($this->picker) {
            // 暂无组件
            $data = [
                'nodeName' => 'a-color-picker',
                'showAlpha' => false,
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
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

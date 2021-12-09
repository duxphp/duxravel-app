<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Widget\Icon;
use Duxravel\Database\Seeders\DatabaseSeeder;

/**
 * Class Text
 * 输入框
 * @package Duxravel\Core\UI\Form
 */
class Text extends Element implements Component
{
    protected string $type = 'text';
    protected array $before = [];
    protected array $after = [];

    /**
     * Text constructor.
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
     * 文本类型
     * @param $name
     * @return $this
     */
    public function type($name): self
    {
        $this->type = $name;
        return $this;
    }

    /**
     * 前置图标
     * @param $content
     * @return $this
     */
    public function beforeIcon($content): self
    {
        $this->before = (new Icon($content))->attr('vSlot:prepend', '')->getRender();
        return $this;
    }

    /**
     * 后置图标
     * @param $content
     * @return $this
     */
    public function afterIcon($content): self
    {
        $this->after = (new Icon($content))->attr('vSlot:append', '')->getRender();
        return $this;
    }

    /**
     * 前置文本
     * @param $content
     * @return $this
     */
    public function beforeText($content): self
    {
        $this->before = [
            'vSlot:prepend' => '',
            'nodeName' => 'span',
            'child' => $content
        ];
        return $this;
    }

    /**
     * 后置文本
     * @param $content
     * @return $this
     */
    public function afterText($content): self
    {
        $this->after = [
            'vSlot:append' => '',
            'nodeName' => 'span',
            'child' => $content
        ];
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render(): array
    {

        $child = [];
        if ($this->before || $this->after) {
            $child = [
                $this->before,
                $this->after
            ];
        }

        $data = [
            'nodeName' => 'a-input',
            'vModel:modelValue' => $this->getModelField(),
            'child' => $child,
            'placeholder' => '请输入' . $this->name,
        ];

        return $data;
    }


}

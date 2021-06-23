<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Widget\Icon;

/**
 * Class Text
 * 输入框
 * @package Duxravel\Core\UI\Form
 */
class Text extends Element implements Component
{
    protected string $type = 'text';
    protected string $beforeIcon = '';
    protected string $afterIcon = '';
    protected string $beforeText = '';
    protected string $afterText = '';

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
        $this->beforeIcon = '<div class="form-input-icon">' . (new Icon($content))->class('w-full h-full')->render() . '</div>';
        return $this;
    }

    /**
     * 后置图标
     * @param $content
     * @return $this
     */
    public function afterIcon($content): self
    {
        $this->afterIcon = '<div class="form-input-icon">' . (new Icon($content))->class('w-full h-full')->render() . '</div>';
        return $this;
    }

    /**
     * 前置文本
     * @param $content
     * @return $this
     */
    public function beforeText($content): self
    {
        $this->beforeText = <<<HTML
            <span class="form-input-label-before">$content</span>
          HTML;
        return $this;
    }

    /**
     * 后置文本
     * @param $content
     * @return $this
     */
    public function afterText($content): self
    {
        $this->afterText = <<<HTML
            <span class="form-input-label-after">$content</span>
          HTML;
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value);

        $this->attr['name'] = $this->field;
        $this->attr['value'] = $value;
        $this->attr['placeholder'] = $this->attr['placeholder'] ?: '请输入' . $this->name;

        $this->class('form-input');

        $textLayout = '';
        $iconLayout = '';
        $iconLayoutEnd = '';
        $textLayoutEnd = '';
        if ($this->afterText || $this->beforeText) {
            if ($this->afterText) {
                $class = 'form-input-group-after';
            }
            if ($this->beforeText) {
                $class = 'form-input-group-before';
            }
            $textLayout = "<div class='form-input-group $class'>";
            $textLayoutEnd = "</div>";
        }

        if ($this->afterIcon || $this->beforeIcon) {
            $iconLayout = '<div class="form-input-suffix">';
            $iconLayoutEnd = '</div>';
        }

        return <<<HTML
            $textLayout
            $this->beforeText
            $iconLayout
            $this->afterIcon
            <input type="$this->type" {$this->toElement()}>
            $this->afterIcon
            $iconLayoutEnd
            $this->afterText
            $textLayoutEnd
        HTML;

    }

}

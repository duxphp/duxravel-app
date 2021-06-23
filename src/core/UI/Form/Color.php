<?php

namespace Duxravel\Core\UI\Form;

/**
 * 颜色选择器
 * Class Color
 * @package Duxravel\Core\UI\Form
 */
class Color extends Element implements Component
{

    protected array $color = [
        'while',
        'black',
        'blue',
        'yellow',
        'green',
        'red',
        'purple',
    ];

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
    public function render($value): string
    {
        $value = $this->getValue($value);

        // 预设选择器
        if (!$this->picker) {
            $value = $value ?: $this->color[0];
            $inner = [];
            foreach ($this->color as $vo) {
                $class = '';
                $style = '';
                if (strpos($vo, '#') === false) {
                    $class = $vo === "while" || $vo === 'black' ? $vo : 'bg-'.$vo.'-900  ring-'.$vo.'-900';
                } else {
                    $style = 'background-color:'.$vo;
                }
                $checked = $vo === $value ? 'checked' : '';
                $inner[] = <<<HTML
                    <label class="form-color "><input name="$this->field" type="radio" value="$vo" $checked><span class="form-color-show $class" style="$style"></span></label>
                HTML;
            }
            $innerHtml = implode('', $inner);
            return <<<HTML
            <div class="flex flex-row space-x-2">
            {$innerHtml}
            </div>
            HTML;
        }

        // 自定义选择器
        $this->class('form-input');
        $this->attr('value', $value);
        $this->attr('name', $this->field);
        $this->attr('type', 'color');
        return "<input {$this->toElement()}>";
    }

}

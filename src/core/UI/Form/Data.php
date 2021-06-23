<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Data
 * 表格数据编辑器
 * @package Duxravel\Core\UI\Form
 */
class Data extends Element implements Component
{
    protected array $column = [];
    protected bool $option = true;
    protected int $number = 0;

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
     * 文本列
     * @param string $name
     * @param string $field
     * @param null $width
     * @return $this
     */
    public function text(string $name, string $field, $width = null): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'text',
            'width' => $width,
        ];
        return $this;
    }

    /**
     * 图片列
     * @param string $name
     * @param string $field
     * @param null $width
     * @return $this
     */
    public function image(string $name, string $field, $width = null): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'image',
            'width' => $width,
        ];
        return $this;
    }

    /**
     * 展示列
     * @param string $name
     * @param string $field
     * @param null $width
     * @return $this
     */
    public function show(string $name, string $field, $width = null): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'show',
            'width' => $width,
        ];
        return $this;
    }

    /**
     * 隐藏列
     * @param string $name
     * @param string $field
     * @return $this
     */
    public function hidden(string $name, string $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'hidden'
        ];
        return $this;
    }

    /**
     * 操作状态
     * @param bool $status
     * @return $this
     */
    public function option(bool $status = true): self
    {
        $this->option = $status;
        return $this;
    }

    /**
     * 最大数量
     * @param int $num
     * @return $this
     */
    public function num(int $num = 0): self
    {
        $this->number = $num;
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $values = $this->getValueArray($value, true);

        $this->attr('data-js', 'form-list');
        $this->attr('data-key', $this->getField());
        $this->attr('data-column', json_encode($this->column));
        $this->attr('data-data', json_encode($values));
        $this->attr('data-option', $this->option);
        $this->attr('data-num', $this->number);

        return "<div {$this->toElement()}></div>";
    }

}

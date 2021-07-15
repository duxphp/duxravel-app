<?php

namespace Duxravel\Core\UI\Form;

/**
 * 表格关联选择器
 * Class Choice
 * @package Duxravel\Core\UI\Form
 */
class Choice extends Element implements Component
{

    protected array $column = [];
    protected array $ajax = [];
    protected bool $option = true;
    protected int $number = 0;

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

    public function ajax($url, $key, $column): self
    {
        $this->ajax = [
            'url' => $url,
            'key' => $key,
            'column' => $column
        ];
        return $this;
    }

    /**
     * 文本列
     * @param $name
     * @param $field
     * @return $this
     */
    public function text($name, $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'text'
        ];
        return $this;
    }

    /**
     * 图片列
     * @param $name
     * @param $field
     * @return $this
     */
    public function image($name, $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'image'
        ];
        return $this;
    }

    /**
     * 展示列
     * @param $name
     * @param $field
     * @return $this
     */
    public function show($name, $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'show'
        ];
        return $this;
    }

    /**
     * 隐藏列
     * @param $name
     * @param $field
     * @return $this
     */
    public function hidden($name, $field): self
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
    public function num(int $num = 0)
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
        $value = $this->getValue($value);
        if ($value instanceof \Illuminate\Database\Eloquent\Collection && $value->count()) {
            $values = $value->toArray();
        } elseif (is_array($value)) {
            $values = $value;
        } else {
            $values = [];
        }

        $this->attr('data-js', 'form-choice');
        $this->attr('data-only', $this->ajax['key']);
        $this->attr('data-key', $this->getField());
        $this->attr('data-column', json_encode($this->column));
        $this->attr('data-data', json_encode($values));
        $this->attr('data-ajax-url', $this->ajax['url']);
        $this->attr('data-option', $this->option);
        $this->attr('data-num', $this->number);

        $ajaxColumn = $this->ajax['column'] ?: [];
        if (is_callable($ajaxColumn)) {
            $ajaxColumn = $ajaxColumn(new ChoiceColumn());
            if (!$ajaxColumn instanceof ChoiceColumn) {
                app_error('Choice component configuration error');
            }
            $ajaxColumn = $ajaxColumn->getData();
        }
        $this->attr('data-ajax-column', json_encode($ajaxColumn));

        return "<div {$this->toElement()}></div>";
    }

}

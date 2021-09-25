<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Widget\Icon;

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
     * @return string
     */
    public function render()
    {
        $url = route('service.image.placeholder', ['w' => 64, 'h' => 64, 't' => $this->attr['placeholder'] ?: '图片']);


        $ajaxColumn = $this->ajax['column'] ?: [];
        if (is_callable($ajaxColumn)) {
            $ajaxColumn = $ajaxColumn(new ChoiceColumn());
            if (!$ajaxColumn instanceof ChoiceColumn) {
                app_error('Choice component configuration error');
            }
            $ajaxColumn = $ajaxColumn->getData();
        }


        return [
            'nodeName' => 'app-choice',
            'vModel:value' => $this->getModelField(),
            'column' => $this->column,
            'ajaxColumn' => $ajaxColumn,
            'only' => $this->ajax['key'],
            'url' => $this->ajax['url'],
            'number' => $this->number,
            'option' => $this->option,
        ];

    }

    public function dataValue($value)
    {
        $value = $this->getValue($value);
        if ($value instanceof \Illuminate\Database\Eloquent\Collection && $value->count()) {
            $values = $value->toArray();
        } elseif (is_array($value)) {
            $values = $value;
        } else {
            $values = [];
        }
        return $values;
    }

}

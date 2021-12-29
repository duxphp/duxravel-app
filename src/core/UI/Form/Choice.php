<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\Exceptions\ErrorException;

/**
 * 表格管理选择器
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
     * @param string   $url
     * @param string   $key
     * @param ChoiceColumn $column
     * @param array    $types
     * @return $this
     */
    public function ajax(string $url, string $key, ChoiceColumn $column, array $types = []): self
    {
        $this->ajax = [
            'url' => $url,
            'key' => $key,
            'column' => $column,
            'type' => $types
        ];
        return $this;
    }

    /**
     * text column
     * @param string $name
     * @param string $field
     * @return $this
     */
    public function text(string $name, string $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'text'
        ];
        return $this;
    }

    /**
     * image column
     * @param string $name
     * @param string $field
     * @return $this
     */
    public function image(string $name, string $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'image'
        ];
        return $this;
    }

    /**
     * show column
     * @param string $name
     * @param string $field
     * @return $this
     */
    public function show(string $name, string $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'show'
        ];
        return $this;
    }

    /**
     * hidden column
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
     * option status
     * @param bool $status
     * @return $this
     */
    public function option(bool $status = true): self
    {
        $this->option = $status;
        return $this;
    }

    /**
     * maximum number
     * @param int $num
     * @return $this
     */
    public function num(int $num = 0): self
    {
        $this->number = $num;
        return $this;
    }

    /**
     * @return array
     * @throws ErrorException
     */
    public function render(): array
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
            'ajaxType' => $this->ajax['type'],
            'key' => $this->ajax['key'],
            'url' => $this->ajax['url'],
            'number' => $this->number,
            'option' => $this->option,
        ];

    }

    /**
     * @param $value
     * @return array|null
     */
    public function dataValue($value): ?array
    {
        $value = $this->getValue($value);
        if ($value instanceof \Illuminate\Database\Eloquent\Collection && $value->count()) {
            $values = $value->toArray();
        } else if (is_array($value)) {
            $values = $value;
        } else {
            $values = [];
        }
        return $values;
    }

}

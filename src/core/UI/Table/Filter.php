<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Form\Cascader;
use Duxravel\Core\UI\Form\Date;
use Duxravel\Core\UI\Form\Daterange;
use Duxravel\Core\UI\Form\Datetime;
use Duxravel\Core\UI\Form\Select;
use Duxravel\Core\UI\Form\Text;
use Duxravel\Core\UI\Table;

/**
 * 表格筛选
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class Filter
{

    protected string $name;
    protected string $field;
    protected $where = true;
    protected Table $layout;
    protected string $type = '';
    protected string $placeholder = '';
    protected bool $quick = false;
    protected ?\Closure $callback;
    protected $data;
    protected $model;
    protected $value;

    /**
     * Filter constructor.
     * @param string $name
     * @param string $field
     * @param callable|string|bool $where
     */
    public function __construct(string $name, string $field, $where = true)
    {
        $this->name = $name;
        $this->field = $field;
        $this->where = $where;
        $this->value = request()->get($field);
    }

    /**
     * 设置父级对象
     * @param Table $layout
     */
    public function setLayout(Table $layout): void
    {
        $this->layout = $layout;
        $this->model = $layout->model();
    }

    /**
     * 级联选择
     * @param $data
     * @param callable|null $callback
     * @return $this
     */
    public function cascader($data, callable $callback = NULL): self
    {
        $this->data = $data;
        $this->type = 'cascader';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 下拉框
     * @param $data
     * @param callable|null $callback
     * @return $this
     */
    public function select($data, callable $callback = NULL): self
    {
        $this->data = $data;
        $this->type = 'select';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 文本库
     * @param string $placeholder
     * @param callable|null $callback
     * @return $this
     */
    public function text(string $placeholder = '', callable $callback = NULL): self
    {
        $this->placeholder = $placeholder;
        $this->type = 'text';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 日期
     * @param string $placeholder
     * @param callable|null $callback
     * @return $this
     */
    public function date(string $placeholder = '', callable $callback = NULL): self
    {
        $this->placeholder = $placeholder;
        $this->type = 'date';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 日期时间
     * @param string $placeholder
     * @param callable|null $callback
     * @return $this
     */
    public function datetime(string $placeholder = '', callable $callback = NULL): self
    {
        $this->placeholder = $placeholder;
        $this->type = 'datetime';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 日期范围
     * @param string $placeholder
     * @param callable|null $callback
     * @return $this
     */
    public function daterange(string $placeholder = '', callable $callback = NULL): self
    {
        $this->placeholder = $placeholder;
        $this->type = 'daterange';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 快捷筛选
     * @return $this
     */
    public function quick(): self
    {
        $this->quick = true;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        switch ($this->type) {
            case 'select':
                $object = new Select($this->name, $this->field, $this->data);
                $object->tip(true);
                break;
            case 'cascader':
                $object = new Cascader($this->name, $this->field, $this->data);
                break;
            case 'date':
                $object = new Date($this->name, $this->field);
                break;
            case 'datetime':
                $object = new Datetime($this->name, $this->field);
                break;
            case 'daterange':
                $object = new Daterange($this->name, $this->field);
                break;
            case 'text':
            default:
                $object = new Text($this->name, $this->field);
        }
        if ($this->callback instanceof \Closure) {
            call_user_func($this->callback, $object);
        }

        return [
            'html' => '<div class="w-full lg:w-44">' . $object->placeholder($this->placeholder)->render($this->value) . '</div>',
            'status' => $this->value !== null,
            'quick' => $this->quick,
            'where' => $this->where,
            'value' => $this->value,
            'field' => $this->field,
            'data' => $this->data
        ];
    }
}

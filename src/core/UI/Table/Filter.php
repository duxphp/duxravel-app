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
class Filter {

    protected string $name;
    protected string $field;
    protected $default;
    protected $where = true;
    protected Table $layout;
    protected string $type = '';
    protected string $placeholder = '';
    protected bool $quick = false;
    protected string $condition = '';
    protected ?\Closure $callback;
    protected $data;
    protected $model;
    protected $value;
    protected $width = 40;

    /**
     * Filter constructor.
     * @param string $name
     * @param string $field
     * @param bool $where
     * @param null $default
     */
    public function __construct(string $name, string $field, $where = true, $default = null) {
        $this->name = $name;
        $this->field = $field;
        $this->where = $where;
        $this->default = $default;
        $this->value = request()->get($field, $this->default);
    }

    /**
     * 设置父级对象
     * @param Table $layout
     */
    public function setLayout(Table $layout): void {
        $this->layout = $layout;
        $this->model = $layout->model();
    }

    /**
     * 设置宽度
     * @return $this
     */
    public function width($width = 40): self {
        $this->width = $width;
        return $this;
    }

    /**
     * 级联选择
     * @param callable|array $data
     * @param callable|null $callback
     * @return $this
     */
    public function cascader($data = [], callable $callback = NULL): self {
        $this->data = $data;
        $this->type = 'cascader';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 下拉框
     * @param callable|array $data
     * @param callable|null $callback
     * @return $this
     */
    public function select($data = [], callable $callback = NULL): self {
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
    public function text(string $placeholder = '', callable $callback = NULL): self {
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
    public function date(string $placeholder = '', callable $callback = NULL): self {
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
    public function datetime(string $placeholder = '', callable $callback = NULL): self {
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
    public function daterange(string $placeholder = '', callable $callback = NULL): self {
        $this->placeholder = $placeholder;
        $this->type = 'daterange';
        $this->callback = $callback;
        return $this;
    }

    /**
     * 快捷筛选
     * @return $this
     */
    public function quick(): self {
        $this->quick = true;
        return $this;
    }

    /**
     * 筛选条件
     * @param $type
     * @return $this
     */
    public function condition($type): self {
        $this->condition = $type;
        return $this;
    }


    /**
     * 执行筛选
     * @param $query
     * @return false
     */
    public function execute($query): bool {
        if ($this->value === null) {
            return false;
        }
        if (is_array($this->value) && empty($this->value)) {
            return false;
        }
        if ($this->where instanceof \Closure) {
            call_user_func($this->where, $query, $this->value, $this->data);
        } elseif ($this->where !== false) {

            $field = is_string($this->where) ? $this->where : $this->field;
            $condition = '=';
            $value = $this->value;

            if ($this->condition === 'like') {
                $condition = 'like';
                $value = '%' . $value . '%';
            }

            $query->where($field, $condition, $value);
        }
        return true;
    }

    /**
     * 渲染组件
     * @return array
     */
    public function render(): array {
        if (!$this->type) {
            $this->layout->filterParams($this->field, $this->value);
            return [];
        }
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

        $object->model('data.filter.');


        $this->layout->filterParams($this->field, $this->value);

        if ($this->callback instanceof \Closure) {
            call_user_func($this->callback, $object);
        }

        $data = [
            'status' => $this->value !== null,
            'quick' => $this->quick,
            'where' => $this->where,
            'value' => $this->value,
            'field' => $this->field,
            'data' => $this->data,
            'name' => $this->name
        ];

        if ($this->quick) {
            $data['render'] = [
                'nodeName' => 'div',
                'class' => 'lg:w-' . $this->width,
                'child' => $object->placeholder($this->placeholder)->getRender()
            ];
        } else {
            $data['render'] = [
                'nodeName' => 'div',
                'class' => 'my-2',
                'child' => [
                    [
                        'nodeName' => 'div',
                        'child' => $this->name,
                    ],
                    [
                        'nodeName' => 'div',
                        'class' => 'mt-2',
                        'child' => $object->placeholder($this->placeholder)->getRender()
                    ]
                ],
            ];
        }

        return $data;
    }
}

<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class TreeSelect
 * @package Duxravel\Core\UI\Form
 */
class TreeSelect extends Element implements Component
{
    protected bool $tip = false;
    protected bool $multi = false;
    protected bool $treeData = false;
    protected string $strategy = 'all';
    protected string $url = '';
    protected string $route = '';
    protected $data;

    /**
     * Select constructor.
     * @param string $name
     * @param string $field
     * @param array|callback|null $data
     * @param string $has
     */
    public function __construct(string $name, string $field, $data = null, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->data = $data;
        $this->has = $has;
    }

    /**
     * 添加选项
     * @param $name
     * @param $id
     * @param $pid
     * @return $this
     */
    public function add($name, $id, $pid): self
    {
        $this->data[] = [
            'name' => $name,
            'id' => $id,
            'pid' => $pid
        ];
        return $this;
    }


    /**
     * 设置动态地址
     * @param string $url
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param string $route
     * @param array  $params
     * @return $this
     */
    public function route(string $route, array $params = []): self
    {
        $this->route = $route;
        $this->url = app_route($route, $params);
        return $this;
    }

    /**
     * 多选组件
     * @return $this
     */
    public function multi(): self
    {
        $this->multi = true;
        return $this;
    }

    /**
     * 树形模式
     * @param bool $bool
     * @return $this
     */
    public function tree(bool $bool = true): self
    {
        $this->treeData = $bool;
        return $this;
    }


    /**
     * 回填方式
     * @param string $model
     * @return $this
     */
    public function strategy(string $model): self
    {
        $this->strategy = $model;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {

        $data = [];
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data, $this);
        }
        if (is_array($this->data)) {
            $data = $this->data;
        }

        if (!$this->treeData) {
            $options = [];
            foreach ($data as $vo) {
                $options[] = [
                    'id' => $vo['id'],
                    'pid' => $vo['pid'],
                    'value' => $vo['id'],
                    'label' => $vo['name'],
                    // 可选参数：专门用于搜索，自行组装并通过data传入该值即可
                    // 使用场景：展示value(title)，但搜索却需要包括简介、ID、标识等
                    'search' => $vo['search'] ?? $vo['name'],
                ];
            }
            $options = \Duxravel\Core\Util\Tree::arr2tree($options, 'id', 'pid', 'children');

        } else {
            $options = $data;
        }

        $data = [
            'nodeName' => 'app-tree-select',
            'nParams' => [
                'multiple' => $this->multi,
                'treeCheckable' => $this->multi,
                'treeCheckedStrategy' => $this->strategy,
                'data' => $options,
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,

            ]
        ];

        if ($this->route) {
            $data['vBind:dataUrl'] = $this->url;
        } else {
            $data['dataUrl'] = $this->url;
        }

        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }

        return $data;
    }

    /**
     * @param $value
     * @return array|mixed
     */
    public function dataValue($value)
    {
        return $this->multi ? array_values(array_filter((array)$this->getValueArray($value))) : $this->getValue($value);
    }

    /**
     * @param $data
     * @return string
     */
    public function dataInput($data): ?string
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

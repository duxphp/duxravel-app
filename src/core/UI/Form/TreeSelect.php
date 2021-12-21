<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

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
     * @param $url
     * @return $this
     */
    public function url($url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 搜索选择
     * @param string $route
     * @return $this
     */
    public function route(string $route, $params = []): self
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
    public function tree(bool $bool = true)
    {
        $this->treeData = $bool;
        return $this;
    }


    /**
     * 回填方式
     * @param string $model
     * @return $this
     */
    public function strategy(string $model)
    {
        $this->strategy = $model;
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render()
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

    public function dataValue($value)
    {
        return $this->multi ? array_values(array_filter((array)$this->getValueArray($value))) : $this->getValue($value);
    }

    public function dataInput($data)
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

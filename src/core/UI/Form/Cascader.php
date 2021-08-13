<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\TreeList;
use Illuminate\Support\Facades\Http;

/**
 * Class Cascader
 * @package Duxravel\Core\UI\Form
 */
class Cascader extends Element implements Component
{
    protected bool $tip = false;
    protected bool $multi = false;
    protected bool $leaf = true;
    protected string $url = '';
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
     * 多选组件
     * @return $this
     */
    public function multi(): self
    {
        $this->multi = true;
        return $this;
    }

    /**
     * 多选组件
     * @return $this
     */
    public function leaf(bool $leaf): self
    {
        $this->leaf = $leaf;
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

        $options = [];
        foreach ($data as $vo) {
            $options[] = [
                'id' => $vo['id'],
                'pid' => $vo['pid'],
                'value' => $vo['id'],
                'label' => $vo['name'],
                //'isLeaf' => $vo['']
            ];
        }

        $options = \Duxravel\Core\Util\Tree::arr2tree($options, 'id', 'pid', 'children');

        $data = [
            'nodeName' => 'app-cascader',
            'class' => 'shadow-sm',
            'nParams' => [
                'cascade' => true,
                'show-path' => true,
                'filterable' => false,
                'clearable' => true,
                'leaf-only' => $this->leaf,
                'multiple' => $this->multi,
                'options' => $options,
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            ]
        ];

        if ($this->url) {
            $data['dataUrl'] = $this->url;
            //$data['nParams']['remote'] = true;
        }

        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }

        return $data;
    }

    /**
     * 获取数据值
     * @param $value
     * @return array|false|string[]|null
     */
    public function dataValue($value)
    {
        return $this->multi ? $this->getValueArray($value) : $this->getValue($value);
    }

    /**
     * 获取输入内容
     * @param $data
     * @return string
     */
    public function dataInput($data): ?string
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

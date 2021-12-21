<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Select
 * @package Duxravel\Core\UI\Form
 */
class Select extends Element implements Component
{
    protected string $url = '';
    protected string $route = '';
    protected string $linkage = '';
    protected string $level = '';
    protected int $tagCount = 0;
    protected bool $tags = false;
    protected bool $tip = false;
    protected bool $search = false;
    protected bool $multi = false;
    protected array $optionRender = [];
    protected $data;

    /**
     * Select constructor.
     * @param string $name
     * @param string $field
     * @param null $data
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
     * @param $value
     * @return $this
     */
    public function add($name, $value): self
    {
        $this->data[$value] = $name;
        return $this;
    }

    /**
     * 默认提示
     * @param bool $tip
     * @return $this
     */
    public function tip(bool $tip = true): self
    {
        $this->tip = $tip;
        return $this;
    }

    /**
     * 搜索
     * @param bool $search
     * @return $this
     */
    public function search(bool $search = true): self
    {
        $this->search = $search;
        return $this;
    }

    /**
     * 搜索选择
     * @param string $url
     * @return $this
     */
    public function url(string $url): self
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
     * 联动选项
     * @param $name
     * @param string $level
     * @return $this
     */
    public function linkage($name, string $level = ''): self
    {
        $this->linkage = (string)$name;
        $this->level = $level;
        return $this;
    }

    /**
     * 多选组件
     * @return $this
     */
    public function multi($count = 0): self
    {
        $this->multi = true;
        $this->tagCount = $count;
        return $this;
    }

    /**
     * 标签组件
     * @return $this
     */
    public function tags(): self
    {
        $this->tags = true;
        $this->multi = true;
        return $this;
    }

    /**
     * 选项渲染
     * @param  array $data JS语法
     * @return $this
     */
    public function optionRender(array $data)
    {
        $this->optionRender = $data;
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
            $data = call_user_func($this->data);
        }
        if (is_array($this->data)) {
            $data = $this->data;
        }

        $options = [];
        foreach ($data as $key => $vo) {
            $options[] = [
                'label' => $vo,
                'value' => $key
            ];
        }

        $object = [
            'nodeName' => 'app-select',
            'nParams' => [
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
                'options' => $options
            ],
        ];

        if ($this->model) {
            $object['vModel:value'] = $this->getModelField();
        }
        $object['nParams']['allowClear'] = true;
        if ($this->multi) {
            $object['nParams']['multiple'] = true;
        }
        if ($this->tags) {
            $object['nParams']['multiple'] = true;
            $object['nParams']['allowCreate'] = true;
        }
        if ($this->url) {
            $object['nParams']['allowSearch'] = true;
            $object['nParams']['filterOption'] = false;
            if ($this->route) {
                $object['vBind:dataUrl'] = $this->url;
            } else {
                $object['dataUrl'] = $this->url;
            }
        }
        if ($this->search) {
            $object['nParams']['allowSearch'] = true;
        }
        if ($this->tagCount) {
            $object['nParams']['maxTagCount'] = $this->tagCount;
        }
        if ($this->optionRender) {
            $object['vRender:optionRender:item'] = $this->optionRender;
        }

        return $object;
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

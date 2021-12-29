<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Select
 * @package Duxravel\Core\UI\Form
 */
class Select extends Element implements Component
{
    protected string $url = '';
    protected string $route = '';
    protected int $tagCount = 0;
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
     * 搜索地址
     * @param string $url
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 路由地址
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
     * 多选
     * @param int $count
     * @return $this
     */
    public function multi(int $count = 0): self
    {
        $this->multi = true;
        $this->tagCount = $count;
        return $this;
    }

    /**
     * 选项渲染
     * @param  array $data JS Node
     * @return $this
     */
    public function optionRender(array $data): self
    {
        $this->optionRender = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
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

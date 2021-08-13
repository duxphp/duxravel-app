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
    protected array $search = [];
    protected string $linkage = '';
    protected string $level = '';
    protected bool $tags = false;
    protected bool $tip = false;
    protected bool $multi = false;
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
     * 搜索选择
     * @param string $url
     * @return $this
     */
    public function search(string $url): self
    {
        $this->search = [
            'url' => $url,
        ];
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
    public function multi(): self
    {
        $this->multi = true;
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
            'class' => 'shadow-sm',
            'nParams' => [
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
                'options' => $options
            ],
        ];

        if ($this->model) {
            $object['vModel:value'] = $this->getModelField();
        }
        if ($this->tip) {
            $object['nParams']['clearable'] = true;
        }
        if ($this->multi) {
            $object['nParams']['multiple'] = true;
        }
        if ($this->tags) {
            $object['nParams']['multiple'] = true;
            $object['nParams']['tag'] = true;
        }

        if ($this->search) {
            $object['nParams']['filterable'] = true;
            $object['nParams']['remote'] = true;
            $object['dataUrl'] = $this->search['url'];
        }

        return $object;
    }

    public function dataValue($value)
    {
        return $this->getValueArray($value);
    }

    public function dataInput($data)
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

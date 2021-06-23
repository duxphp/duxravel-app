<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Cascader
 * @package Duxravel\Core\UI\Form
 */
class Cascader extends Element implements Component
{
    protected bool $tip = false;
    protected bool $multi = false;
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
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $values = $this->getValueArray($value);
        $this->class[] = 'form-select';
        $this->attr['data-js'] = 'form-cascader';
        $this->attr['data-url'] = $this->url;
        $this->attr['name'] = $this->multi ? $this->field . '[]' : $this->field;
        $this->attr['data-placeholder'] = $this->attr['placeholder'] ?: '请选择' . $this->name;
        if ($this->multi) {
            $this->attr['multiple'] = '';
        }
        $data = [];
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data, [$values]);
        }
        if (is_array($this->data)) {
            $data = $this->data;
        }

        $inner = [];
        foreach ($data as $vo) {
            $selected = $values !== null && in_array($vo['id'], $values) ? 'selected' : '';
            $inner[] = <<<HTML
                <option $selected data-pid="{$vo['pid']}" value="{$vo['id']}">{$vo['name']}</option>
            HTML;
        }
        $innerHtml = implode('', $inner);

        return <<<HTML
            <select {$this->toElement()}>
                <option></option>
                $innerHtml
            </select>
        HTML;
    }

    /**
     * 获取输入内容
     * @param $data
     * @return string
     */
    public function getInputData($data): ?string
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

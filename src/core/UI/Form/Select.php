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
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $values = $this->getValueArray($value);

        $this->class('form-select');
        $this->attr('data-js', 'form-select');
        $this->attr('name', $this->multi ? "$this->field[]" : $this->field);
        $this->attr('data-placeholder', $this->attr['placeholder'] ?: '请选择' . $this->name);
        if ($this->search) {
            $this->attr('data-search', $this->search['url']);
            $this->attr('data-value', is_array($values) ? implode(',', $values) : $values);
            $this->tip();
        }
        if ($this->linkage) {
            $this->attr('data-linkage', $this->linkage);
            $this->attr('data-level', $this->level);
        }
        if ($this->level) {
            $this->attr('data-level', $this->level);
        }
        if ($this->tip) {
            $this->attr('data-clear', true);
        }
        if ($this->multi) {
            $this->attr('multiple', '');
        }
        if ($this->tags) {
            $this->attr('data-tags', true);
        }

        $data = [];
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data, $values);
        }
        if (is_array($this->data)) {
            $data = $this->data;
        }

        $inner = [];
        $inner[] = $this->tip && !$this->tags ? '<option></option>' : '';
        if ($this->tags && $values) {
            foreach ($values as $vo) {
                $inner[] = "<option selected value='$vo'>$vo</option>";
            }
        }
        foreach ($data as $key => $vo) {
            $selected = $value !== null && in_array($key, $values) ? 'selected' : '';
            $inner[] = "<option $selected value='$key'>$vo</option>";
        }
        $innerHtml = implode('', $inner);
        return <<<HTML
            <select {$this->toElement()}>
            $innerHtml
            </select>
        HTML;
    }

    public function getInputData($data)
    {
        return is_array($data) ? implode(',', $data) : $data;
    }

}

<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Radio
 * @package Duxravel\Core\UI\Form
 */
class Radio extends Element implements Component
{
    protected bool $tip = false;
    protected array $box = [];
    protected $data = [];
    protected string $switch = '';

    /**
     * Select constructor.
     * @param string $name
     * @param string $field
     * @param  null|array|callable  $data
     * @param string $has
     */
    public function __construct(string $name, string $field, $data = [], string $has = '')
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
        $this->data[$name] = $value;
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
     * 切换组件
     * @param $group
     * @return $this
     */
    public function switch($group): self
    {
        $this->switch = $group;
        return $this;
    }

    /**
     * 类型选择
     * @param array $data
     * @return $this
     */
    public function box(array $data): self
    {
        $this->box = $data;
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value);

        $data = [];
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data);
        }
        if (is_array($this->data)) {
            $data = $this->data;
        }
        $value = $value ?? array_key_first($data);

        $this->attr('name', $this->field);
        if ($this->switch) {
            $this->attr('data-js', 'form-change');
            $this->attr('data-group', $this->switch);
        }

        $inner = [];
        if ($this->box) {
            $this->class('form-radiobox ');
            foreach ($data as $key => $vo) {
                $checked = $key == $value ? 'checked' : '';
                $name = is_array($this->box[$key]) ? $this->box[$key]['name'] : $this->box[$key];
                $inner[] = <<<HTML
                    <label class="form-radiobox-item">
                        <input type="radio" value="$key" class="form-radiobox-radio" $checked {$this->toAttr()} {$this->toStyle()}>
                        <div class="form-radiobox-label">
                            <div class="items-center mr-4">
                                <span class="form-radio"></span>
                            </div>
                            <div class="items-center">
                                <div class="form-radiobox-title mb-1">$vo</div>
                                <div class="d-block text-gray-500">$name</div>
                            </div>
                        </div>
                    </label>
                HTML;
            }
        } else {
            $this->class('flex gap-4 flex-col lg:flex-row');
            foreach ($data as $key => $vo) {
                $checked = $key == $value ? 'checked' : '';
                $inner[] = <<<HTML
                    <label class="flex items-center">
                        <input class="form-radio text-blue-900 border-gray-400 focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"  $checked type="radio"  {$this->toStyle()} {$this->toAttr()} value="$key">
                        <div class="ml-2">$vo</div>
                    </label>
                HTML;
            }
        }

        $innerHtml = implode('', $inner);
        return <<<HTML
            <div {$this->toClass()}>
            $innerHtml
            </div>
        HTML;
    }

}

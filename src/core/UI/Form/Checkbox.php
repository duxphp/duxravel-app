<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Checkbox
 * @package Duxravel\Core\UI\Form
 */
class Checkbox extends Element implements Component
{

    protected $data;
    protected string $switch = '';

    /**
     * Select constructor.
     * @param  string  $name
     * @param  string  $field
     * @param  null|array|callable  $data
     * @param  string  $has
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
        $this->data[] = [
            'name' => $name,
            'value' => $value
        ];
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
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $values = $this->getValueArray($value);

        $this->class('flex gap-4 flex-col lg:flex-row');
        $this->attr('name', "$this->field[]");

        $data = [];
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data);
        }
        if (is_array($this->data)) {
            $data = $this->data;
        }

        if ($this->switch) {
            $this->attr('data-js', 'form-change');
            $this->attr('data-group', $this->switch);
        }
        $values = $values ?: array_key_first((array) $data);

        $inner = [];
        foreach ($data as $key => $vo) {
            $selected = $value !== null && in_array($key, $values) ? 'checked' : '';
            $inner[] = <<<HTML
                    <label class="flex items-center ">
                        <input type="checkbox" class="form-checkbox text-blue-900 border-gray-400 focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"  {$selected} {$this->toStyle()} {$this->toAttr()} value="{$key}">
                        <div class="ml-2">$vo</div>
                    </label>
            HTML;
        }
        $innerHtml = implode('', $inner);
        return <<<HTML
            <div {$this->toClass()}>
                $innerHtml
            </div>
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

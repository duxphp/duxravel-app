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
    protected array $box = [];
    protected $data = [];
    protected string $switch = '';

    /**
     * Select constructor.
     * @param string $name
     * @param string $field
     * @param null|array|callable $data
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
        $this->data = $data;


        $child = [];
        foreach ($data as $key => $vo) {
            $child[] = [
                'nodeName' => 'a-radio',
                'child' => $vo,
                //'key' => $key,
                'value' => $key,
            ];
        }

        $data = [
            'nodeName' => 'a-radio-group',
            'name' => $this->field,
            'vModel:modelValue' => $this->getModelField(),
            'child' => $child
        ];

        return $data;
    }

    public function dataValue($value)
    {
        return $this->getValue($value) ?? array_key_first((array)$this->data);
    }

}

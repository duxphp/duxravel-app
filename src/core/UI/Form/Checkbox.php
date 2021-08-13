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
     * @param string $name
     * @param string $field
     * @param null|array|callable $data
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

        $child = [];
        foreach ($data as $key => $vo) {
            $child[] = [
                'nodeName' => 'n-checkbox',
                'value' => $key,
                'label' => $vo
            ];
        }

        $data = [
            'nodeName' => 'n-checkbox-group',
            'name' => $this->field,
            'child' => [
                'nodeName' => 'n-space',
                'class' => 'flex',
                'child' => $child
            ]
        ];

        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }

        return $data;
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

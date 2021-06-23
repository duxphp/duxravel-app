<?php

namespace Duxravel\Core\UI\Form;
/**
 * 表格关联选择器列
 * Class Choice
 * @package Duxravel\Core\UI\Form
 */
class ChoiceColumn
{
    /**
     * @var array
     */
    private array $column;

    /**
     * 文本列
     * @param $name
     * @param $field
     * @return $this
     */
    public function text($name, $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'text'
        ];
        return $this;
    }

    /**
     * 图片列
     * @param $name
     * @param $field
     * @return $this
     */
    public function image($name, $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'image'
        ];
        return $this;
    }


    /**
     * 获取配置
     * @return array
     */
    public function getData(): array
    {
        return $this->column;
    }

}

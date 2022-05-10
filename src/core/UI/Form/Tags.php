<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Tags
 * 标签输入框
 * @package Duxravel\Core\UI\Form
 */
class Tags extends Element implements Component
{
    protected int $limit = 0;

    /**
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
    }

    /**
     * @param $num
     * @return $this
     */
    public function limit($num): self
    {
        $this->limit = $num;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $data = [
            'nodeName' => 'a-input-tag',
            'vModel:modelValue' => $this->getModelField(),
            'placeholder' => $this->attr['placeholder'] ?: '请输入' . $this->name,
            'allowClear' => true
        ];
        if ($this->limit) {
            $data['maxTagCount'] = $this->limit;
        }

        if($this->replace != ''){
            $data['vStringReplace'] = $this->replace;
        }

        return $data;
    }

    /**
     * @param $value
     * @return array
     */
    public function dataValue($value): array
    {
        return array_values(array_filter((array)$this->getValueArray($value)));
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

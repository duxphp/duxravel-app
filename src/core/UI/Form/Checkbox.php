<?php

namespace Duxravel\Core\UI\Form;

/**
 * 多选框
 * @package Duxravel\Core\UI\Form
 */
class Checkbox extends Element implements Component
{

    protected $data;

    /**
     * Select constructor.
     * @param string              $name
     * @param string              $field
     * @param null|array|callable $data
     * @param string              $has
     */
    public function __construct(string $name, string $field, $data = null, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->data = $data;
        $this->has = $has;
    }

    /**
     * add data
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

        $child = [];
        foreach ($data as $key => $vo) {
            $child[] = [
                'nodeName' => 'a-checkbox',
                'value' => $key,
                'child' => $vo,
            ];
        }

        $data = [
            'nodeName' => 'a-checkbox-group',
            'child' => $child
        ];

        if ($this->model) {
            $data['vModel:model-value'] = $this->getModelField();
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

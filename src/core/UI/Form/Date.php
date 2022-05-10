<?php

namespace Duxravel\Core\UI\Form;

/**
 * 时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Date extends Element implements Component
{

    protected ?string $type = null;

    private array $types = [
        'year',
        'month',
    ];

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
     * @param string $type
     * @return $this
     */
    public function type(string $type): self
    {
        if (!in_array($type, $this->types)) {
            throw new \RuntimeException('There is no type "' . $type . '"');
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $data = [
            'nodeName' => 'a-date-picker',
            'allowClear' => true,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            'vModel:modelValue' => $this->getModelField()
        ];
        if ($this->type) {
            $data['nodeName'] = 'a-' . $this->type . '-picker';
        }

        if($this->replace != ''){
            $data['vStringReplace'] = $this->replace;
        }

        return $data;
    }

    /**
     * @param $data
     * @return string|null
     */
    public function dataInput($data): ?string
    {
        return $data ? date('Y-m-d', strtotime($data)) : null;
    }

    /**
     * @param $data
     * @return string|null
     */
    public function dataValue($data): ?string
    {
        $data = $this->getValue($data);
        return $data ?: null;
    }

}

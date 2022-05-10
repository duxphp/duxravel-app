<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Tools;

/**
 * 时间范围选择器
 * @package Duxravel\Core\UI\Form
 */
class Daterange extends Element implements Component
{

    private string $stopField;

    /**
     * Text constructor.
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
     * 设置结束字段
     * @param $field
     * @return $this
     */
    public function stopField($field): self
    {
        $this->stopField = $field;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $data = [
            'nodeName' => 'a-range-picker',
            'allowClear' => true,
            'vModel:modelValue' => $this->getModelField()
        ];

        if($this->replace != ''){
            $data['vStringReplace'] = $this->replace;
        }

        return $data;
    }

    /**
     * @param $value
     * @return array
     */
    public function appendInput($value): array
    {
        if (!$this->stopField) {
            return [];
        }
        $data = [];
        $data[$this->stopField] = is_array($value) && $value[1] ? date('Y-m-d H:i:s', strtotime($value[1])) : null;
        return $data;
    }

    /**
     * @param $value
     * @return string|null
     */
    public function dataInput($value): ?string
    {
        if ($this->stopField) {
            return is_array($value) && $value[0] ? date('Y-m-d H:i:s',strtotime($value[0])) : null;
        }
        return is_array($value) ? date('Y-m-d H:i:s', strtotime($value[0])) . ',' . date('Y-m-d H:i:s', strtotime($value[1])) : null;
    }

    /**
     * @param $value
     * @param $info
     * @return array|null
     */
    public function dataValue($value, $info): ?array
    {
        $value = $this->getValue($value);
        $data = [];
        if ($this->stopField) {
            $data[] = $value ?: null;
            $stopValue = Tools::parsingArrData($info, $this->stopField);
            $data[] = $stopValue ?: null;
            return $data;
        }
        if ($value) {
            $data = explode(',', $value);
        }
        return $data ? [$data[0], $data[1]] : null;
    }


}

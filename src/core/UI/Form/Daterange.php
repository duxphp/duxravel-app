<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Daterange
 * 时间范围选择器
 * @package Duxravel\Core\UI\Form
 */
class Daterange extends Element implements Component
{

    private $stopField;

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
    public function stopField($field)
    {
        $this->stopField = $field;
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'a-range-picker',
            'allowClear' => true,
            'vModel:modelValue' => $this->getModelField()
        ];
        return $data;
    }

    public function appendInput($value)
    {
        if (!$this->stopField) {
            return [];
        }
        $data = [];
        $data[$this->stopField] = is_array($value) && $value[1] ? strtotime($value[1]) : null;
        return $data;
    }

    public function dataInput($value)
    {
        if ($this->stopField) {
            return is_array($value) && $value[0] ? strtotime($value[0]) : null;
        }
        return is_array($value) ? strtotime($value[0]) . ',' . strtotime($value[1]) : null;
    }

    public function dataValue($value, $info)
    {
        $value = $this->getValue($value);
        if ($this->stopField) {
            $data = [];
            $data[] = $value ? date('Y-m-d H:i:s', $value) : null;
            $stopValue = Tools::parsingArrData($info, $this->stopField);
            $data[] = $stopValue ? date('Y-m-d H:i:s', $stopValue) : null;
            return $data;
        }
        $data = [];
        if ($value) {
            $data = explode(',', $value);
        }
        return $data ? [date('Y-m-d H:i:s', $data[0]), date('Y-m-d H:i:s', $data[1])] : null;
    }


}

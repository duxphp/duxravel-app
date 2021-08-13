<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\TreeList;
use Illuminate\Support\Facades\Http;

/**
 * Class Area
 * @package Duxravel\Core\UI\Form
 */
class Area extends Element implements Component
{
    protected bool $multi = false;
    protected $map = [
        'province' => 'province',
        'city' => 'city',
        'region' => 'region',
        'street' => 'street',
    ];

    /**
     * Select constructor.
     * @param string $name
     * @param string $field
     * @param array|callback|null $data
     * @param string $has
     */
    public function __construct(string $name, array $map = [], string $has = '')
    {
        if ($map) {
            $this->map = $map;
        }
        $this->name = $name;
        $this->field = end($this->map);
        $this->has = $has;
    }

    /**
     * 多选组件
     * @return $this
     */
    public function multi(): self
    {
        $this->multi = true;
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render(): array
    {
        $data = [
            'nodeName' => 'app-cascader',
            'nParams' => [
                'cascade' => true,
                'show-path' => true,
                'filterable' => false,
                'clearable' => true,
                'leaf-only' => true,
                'multiple' => $this->multi,
                'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
            ],
            'dataUrl' => route('service.area', ['level' => count($this->map)]),
        ];
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

    /**
     * 获取数据值
     * @param $value
     * @return array|false|string[]|null
     */
    public function dataValue($value)
    {
        return $value;
    }

    public function appendInput($data)
    {
        $info = \Duxravel\Core\Model\Area::where(['code' => $data])->first();

        $code = $info->parent_code;
        if ($info->level > 3) {
            $region = \Duxravel\Core\Model\Area::where(['code' => $code])->first();
            $code = $region['parent_code'];
        }
        if ($info->level > 2) {
            $city = \Duxravel\Core\Model\Area::where(['code' => $code])->first();
            $code = $city['parent_code'];
        }
        if ($info->level > 1) {
            $province = \Duxravel\Core\Model\Area::where(['code' => $code])->first();
        }
        $data = [];
        if ($region) {
            $data[$this->map['region']] = $region->code;
        }
        if ($city) {
            $data[$this->map['city']] = $city->code;
        }
        if ($province) {
            $data[$this->map['province']] = $province->code;
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

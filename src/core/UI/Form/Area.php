<?php

namespace Duxravel\Core\UI\Form;

/**
 * 地区选择器
 * @package Duxravel\Core\UI\Form
 */
class Area extends Element implements Component
{
    protected bool $multi = false;
    protected array $map = [
        'province' => 'province',
        'city' => 'city',
        'region' => 'region',
        'street' => 'street',
    ];

    /**
     * @param string $name
     * @param array  $map
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
     * @return $this
     */
    public function multi(): self
    {
        $this->multi = true;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $data = [
            'nodeName' => 'app-cascader',
            'nParams' => [
                'path-mode' => true,
                'clearable' => true,
                'allow-search' => true,
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
     * @param $data
     * @return array
     */
    public function appendInput($data): array
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

}

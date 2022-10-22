<?php

namespace Duxravel\Core\UI\Form;

/**
 * 地图
 * @package Duxravel\Core\UI\Form
 */
class Location extends Element implements Component
{
    protected array $map = [
        'province'     => 'province',
        'city'         => 'city',
        'district'     => 'district',
        'street'       => 'street',
        'streetNumber' => 'streetNumber',
        'address'      => 'address',
        'lat'          => 'lat',
        'lng'          => 'lng'
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
     * @return array
     */
    public function render(): array
    {

        $child = [];

        return [
            'nodeName' => 'app-map',
            'vModel:value' => $this->getModelField(),
            'child' => $child,
            'placeholder' => '请输入' . $this->name,
        ];
    }
    /**
     * @param $data
     * @return array
     */
    public function appendInput($data): array
    {
        $ret = [];
        if ($data->province) {
            $ret[$this->map['province']] = $data->province;
        }
        if ($data->city) {
            $ret[$this->map['city']] = $data->city;
        }
        if ($data->district) {
            $ret[$this->map['district']] = $data->district;
        }
        if ($data->street) {
            $ret[$this->map['street']] = $data->street;
        }
        if ($data->streetNumber) {
            $ret[$this->map['streetNumber']] = $data->streetNumber;
        }
        if ($data->address) {
            $ret[$this->map['address']] = $data->address;
        }
        if ($data->lat) {
            $ret[$this->map['lat']] = $data->lat;
        }
        if ($data->lng) {
            $ret[$this->map['lng']] = $data->lng;
        }
        return $ret;
    }
    /**
     * @param $data
     * @return string|null
     */
    public function dataValue($data): ?array
    {
        $data = $this->getValue($data);
        if (empty($data)) {
            return [];
        } else {
            if (is_array($data)) {
                return $data;
            }
            return json_decode($data, true);
        }
    }
}

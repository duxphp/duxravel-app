<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Map
 * 地图坐标选择器
 * @package Duxravel\Core\UI\Form
 */
class Map extends Element implements Component
{

    protected string $key = '';
    protected array $config = [
        'province' => 'province',
        'city' => 'city',
        'region' => 'region',
        'address' => 'address'
    ];

    /**
     * Map constructor.
     * @param string $name
     * @param string $field
     * @param array $maps
     * @param string $has
     */
    public function __construct(string $name, string $field, array $maps = [], string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->key = config('dux.map_baidu_key');
        if ($maps) {
            $this->config = $maps;
        }
    }


    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value);
        $this->style('height', '400px');
        return <<<HTML
            <input type="hidden" name="$this->field" value="$value"><div {$this->toElement()} data-js="form-map" data-key="$this->key" data-position="$this->field" data-province="$this->config['province']" data-city="$this->config['city']" data-region="$this->config['region']" data-address="$this->config['address']"></div>
        HTML;
    }

}

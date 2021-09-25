<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Spec
 * @package Duxravel\Core\UI\Form
 */
class Spec extends Element implements Component
{
    protected $keysData;
    protected $specData;
    protected $skuList;

    /**
     * Spec constructor.
     * @param  string  $name
     * @param  string  $field
     * @param  null  $keysData  货品字段
     * @param  null  $specData  规格数据
     * @param  null  $skuList  货品数据
     * @param  string  $has
     */
    public function __construct(string $name, string $field, $keysData = null, $specData = null, $skuList = null, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->keysData = $keysData;
        $this->specData = $specData;
        $this->skuList = $skuList;
        $this->has = $has;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value)
    {
        $value = $this->getValue($value);

        $keysData = $this->getCallbackArray($this->keysData, $value);
        $specData = $this->getCallbackArray($this->specData, $value) ?: $value['spec'];
        $skuList = $this->getCallbackArray($this->skuList, $value) ?: $value['sku'];

        $this->attr('data-js', 'system-spec');
        $this->attr('data-key', $this->getField());
        $this->attr('data-keys-data', json_encode($keysData, JSON_THROW_ON_ERROR));
        $this->attr('data-spec-data', json_encode($specData));
        $this->attr('data-sku-list', json_encode($skuList));

        return "<div {$this->toElement()}></div>";
    }



}

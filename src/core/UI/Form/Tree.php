<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Composite;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Tools;

/**
 * Class Tree
 * @package Duxravel\Core\UI\Table
 */
class Tree extends Element implements Component
{

    protected $data;

    /**
     * Tree constructor.
     * @param string $name
     * @param string $field
     * @param null $data
     * @param string $has
     */
    public function __construct(string $name, string $field, $data = null, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->data = $data;
        $this->has = $has;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $values = $this->getValueArray($value);

        $data = [];
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data, $values);
        }
        if (is_array($this->data)) {
            $data = collcet($this->data);
        }

        $data = $data->map(function ($item) {
            if (!$item->parent) {
                $item->parent = '#';
            }
            $item->state = [
                'selected' => $item->id !== null && in_array($item->id, (array) $values) ? true : false
            ];
            return $item;
        });

        $json = json_encode($data, JSON_THROW_ON_ERROR);

        return <<<HTML
            <div class="border border-gray-300 p-4 max-h-56 overflow-auto">
                <input type="hidden" name="$this->field" value="$value">
                <div {$this->toElement()} data-js="form-tree" data-data='$json'></div>
            </div>
        HTML;

    }

}

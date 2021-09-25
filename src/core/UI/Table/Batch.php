<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Form\Select;

/**
 * 操作批量
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class Batch
{

    protected array $button = [];
    protected array $select = [];
    protected array $url = [];

    /**
     * 普通按钮
     * @param string $name
     * @param string $type
     * @param string $route
     * @param array $params
     * @param string $type
     * @return $this
     */
    public function button(string $name, $type = '', string $route = '', array $params = [], string $btnType = 'default'): self
    {
        $params['bath_type'] = $type;
        $url = route($route, $params);
        $this->button[] = [
            'nodeName' => 'n-button',
            'type' => $btnType,
            'size' => 'small',
            'child' => $name,
            'vOn:click' => "checkAction('$url')"
        ];
        return $this;
    }

    /**
     * 渲染组件
     * @return array
     */
    public function render(): array
    {
        return $this->button;

        $html = [];
        foreach ($this->select as $key => $select) {
            $inner = $select->class('custom-select')->render(null);
            $html[] = <<<HTML
                <form class="input-group" method="get" action="{$this->url[$key]}">
                $inner
                <div class="input-group-append"><button type="submit" data-submit class="btn-blue">执行</button></div>
                </form>
            HTML;
        }
        foreach ($this->button as $button) {
            $html[] = $button->render($data);
        }
        return $html;
    }

}

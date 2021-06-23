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
     * @param string $route
     * @param array $params
     * @param string $type
     * @return Link
     */
    public function button(string $name, string $route = '', array $params = [], string $type = 'primary'): Link
    {
        $link = new Link($name, $route, $params);
        $link->button($type);
        $link->attr('data-link', '');
        $this->button[] = $link;
        return $link;
    }

    /**
     * 菜单按钮
     * @param  string  $name
     * @param  string  $route
     * @param  array   $data
     * @return Select
     */
    public function select(string $name, string $route = '', array $data = []): Select
    {
        $data = array_merge([null => $name], $data);
        $select = (new Select($name, 'type', $data));
        $this->select[] = $select;
        $this->url[] = route($route);
        return $select;
    }

    /**
     * 渲染组件
     * @return array
     */
    public function render(): array
    {
        $data = request()->all();
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

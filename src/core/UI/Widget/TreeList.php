<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Append\Element;

/**
 * Class TreeList
 * @package Duxravel\Core\UI\Widget
 */
class TreeList extends Widget
{

    private $data;
    private string $name;
    private string $title;
    private string $route;
    private array $params;
    private string $key;

    public function __construct(string $title, $data, string $key, string $route = '', array $params = [], string $name = 'name', callable $callback = NULL)
    {
        $this->callback = $callback;
        $this->data = $data;
        $this->name = $name;
        $this->title = $title;
        $this->key = $key;
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return view('vendor.duxphp.duxravel-app.src.core.UI.View.Widget.tree-list', [
            'data' => $this->data,
            'name' => $this->name,
            'title' => $this->title,
            'route' => $this->route,
            'params' => $this->params,
            'key' => $this->key
        ])->render();
    }

}

<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Form\Date;
use Duxravel\Core\UI\Form\Daterange;
use Duxravel\Core\UI\Form\Datetime;
use Duxravel\Core\UI\Form\Select;
use Duxravel\Core\UI\Form\Text;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget\Icon;

/**
 * 类型筛选
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class FilterType
{

    protected string $name;
    protected ?\Closure $callback;
    protected ?string $url;
    protected ?\Closure $where;
    protected string $icon = '';
    protected ?int $num = null;
    protected ?Table $layout;
    protected $model;

    /**
     * FilterType constructor.
     * @param string $name
     * @param callable|null $where
     * @param string|null $url
     */
    public function __construct(string $name, callable $where = null, string $url = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->where = $where;
    }

    public function setLayout(Table $layout): void
    {
        $this->layout = $layout;
        $this->model = $layout->model();
    }

    public function num($num = 0): self
    {
        $this->num = $num;
        return $this;
    }

    public function icon($content): self
    {
        $this->icon = $content;
        return $this;
    }

    public function render($key): string
    {
        $url = $this->url;
        if (!$url) {
            $url = route(request()->route()->getName(), ['type' => $key]);
        }
        $type = request()->get('type');
        if ($this->where instanceof \Closure && $type == $key) {
            $this->layout->filterParams('type', $type);
            call_user_func($this->where, $this->model);
        }

        if ($this->icon instanceof Icon) {
            $this->icon = $this->icon->class('w-full h-full')->render();
        }
        if ($this->icon) {
            $icon = '<div class="mr-2 w-5 h-5">' . $this->icon . '</div>';
        }
        $num = '';
        if ($this->num !== null) {
            $num = ' <span class="rounded-full bg-red-900 px-1 py-0 ml-1 text-xs text-white">' . $this->num . '</span>';
        }
        $active = $type == $key ? 'border-blue-900 text-blue-900' : '';
        return <<<HTML
            <a href="$url" class="border-b-2 border-white  block py-4 flex-shrink-0 flex items-center text-gray-600 hover:text-blue-900 $active">
            $icon
            $this->name
            $num
            </a>
        HTML;

    }
}

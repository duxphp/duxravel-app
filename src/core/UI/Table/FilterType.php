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
    public function __construct(string $name, callable $where = null)
    {
        $this->name = $name;
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

    public function render($key)
    {
        $type = request()->get('type', 0);

        $this->layout->filterParams('type', $type);

        if ($this->where instanceof \Closure && $type == $key) {
            call_user_func($this->where, $this->model);
        }

        return [
            'nodeName' => 'n-radio-button',
            'value' => $key,
            'child' => [
                'nodeName' => 'div',
                'class' => 'inline-flex items-center gap-2',
                'child' => [
                    (new Icon($this->icon))->size(18)->getRender(),
                    [
                        'nodeName' => 'div',
                        'child' => $this->name
                    ]
                ]
            ]
        ];

    }
}

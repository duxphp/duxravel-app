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
    protected $value;

    /**
     * FilterType constructor.
     * @param string $name
     * @param callable|null $where
     * @param string|null $url
     */
    public function __construct(string $name, callable $where = null, int $value = 0)
    {
        $this->name = $name;
        $this->where = $where;
        $this->value = $value;
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

    public function execute($query, $key)
    {
        if ($this->where instanceof \Closure && $this->value == $key) {
            call_user_func($this->where, $this->model);
        }
    }

    public function render($key)
    {
        return [
            'nodeName' => 'a-radio',
            'value' => $key,
            'child' => $this->name,
            'child' => [
                $this->icon ? [
                    'nodeName' => $this->icon
                ]: [],
                [
                    'nodeName' => 'span',
                    'child' => ' ' . $this->name
                ]
            ]
        ];

    }
}

<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Append\Element;

/**
 * Class Progress
 * @package Duxravel\Core\UI\Widget
 */
class Progress extends Widget
{
    use Element;

    private $value;
    private string $color = 'default';
    private bool $number = true;


    /**
     * Progress constructor.
     * @param $value
     * @param callable|null $callback
     */
    public function __construct($value, callable $callback = NULL)
    {
        $this->value = $value;
        $this->callback = $callback;
    }

    public function color($color): self
    {
        $this->color = $color;
        return $this;
    }

    public function number($status = true): self
    {
        $this->number = (bool)$status;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $node = [
            'nodeName' => 'n-progress',
            'type' => 'line',
            'status' => $this->color,
        ];
        if (is_numeric($this->value)) {
            $node['percentage'] = $this->value;
        }else {
            $node['vBind:percentage'] = $this->value;
        }
        return $node;

    }

}

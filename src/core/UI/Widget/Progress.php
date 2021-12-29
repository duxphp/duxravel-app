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
    private string $size = 'medium';


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

    /**
     * @param string $color
     * @return $this
     */
    public function color(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @param string $size
     * @return $this
     */
    public function size($size = 'medium'): self
    {
        $this->size = (bool)$size;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $node = [
            'nodeName' => 'a-progress',
            'size' => $this->size,
            'status' => $this->color,
        ];
        if (is_numeric($this->value)) {
            $node['percent'] = $this->value;
        }else {
            $node['vBind:percent'] = $this->value;
        }
        return $node;

    }

}

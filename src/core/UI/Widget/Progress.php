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

    private int $max;
    private int $value;
    private int $color;
    private bool $number = true;


    /**
     * Progress constructor.
     * @param int $max
     * @param int $value
     * @param callable|null $callback
     */
    public function __construct(int $max, int $value, callable $callback = NULL)
    {
        $this->max = $max;
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
     * @return string
     */
    public function render(): string
    {
        $rate = round($this->value / $this->max * 100);
        $number = $this->number ? "<span class='flex-none w-8 ml-4 text-gray-500'>$rate%</span>" : '';
        return <<<HTML
            <div class="flex items-center">
                <div class="flex-grow rounded-full h-3 box-border border border-gray-300 relative ">
                    <div class="bg-$this->color-900  h-3 rounded-l-full absolute  -top-px -left-px" style="width: $rate% "></div>
                </div>
            </div>
            $number
        HTML;

    }

}

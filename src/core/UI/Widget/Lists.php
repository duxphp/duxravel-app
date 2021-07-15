<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Lists
 * @package Duxravel\Core\UI\Widget
 */
class Lists extends Widget
{

    private array $data;
    private int $col;

    /**
     * Lists constructor.
     * @param $data
     * @param int $col
     * @param callable|null $callback
     */
    public function __construct($data, int $col = 2, callable $callback = NULL)
    {
        $this->data = $data;
        $this->col = $col;
        $this->callback = $callback;
    }


    /**
     * @return string
     */
    public function render(): string
    {

        $inner = [];
        $count = count($this->data);
        $i = 0;
        foreach ($this->data as $item) {
            $i++;
            if ($count === $i) {
                $border = '';
            } else {
                $border = 'border-b border-gray-300';
            }
            $inner[] = "<div class='grid grid-cols-{$this->col} gap-4 py-4 px-4 $border'>";
            if (is_array($item)) {
                foreach ($item as $vo) {
                    $inner[] = "<div>$vo</div>";
                }
            } else {
                $inner[] = "<div>$item</div>";
            }
            $inner[] = '</div>';
        }
        $innerHtml = implode('', $inner);


        return <<<HTML
            <div {$this->toElement()}>
                    $innerHtml
            </div>
        HTML;
    }

}

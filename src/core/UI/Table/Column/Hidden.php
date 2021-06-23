<?php

namespace Duxravel\Core\UI\Table\Column;


/**
 * Class Hidden
 */
class Hidden implements Component
{

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {
        return '<div><span class="mr-2" hidden>' . $value . '</span><span class="border border-blue-900 rounded py-1 px-2 bg-blue-900 text-white cursor-pointer" data-js="show-hidden">显示</span></div>';
    }

}

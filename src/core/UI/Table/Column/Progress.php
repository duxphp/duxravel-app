<?php

namespace Duxravel\Core\UI\Table\Column;


/**
 * Class Progress
 */
class Progress implements Component
{

    private string $color;

    /**
     * Progress constructor.
     * @param string $color
     * @param int $max
     */
    public function __construct(string $color = 'default')
    {
        $this->color = $color;
    }

    /**
     * @param $value
     * @param $data
     * @return array
     */
    public function render($field): array
    {
        return (new \Duxravel\Core\UI\Widget\Progress("rowData.record['$field']"))->color($this->color)->render();
    }

}

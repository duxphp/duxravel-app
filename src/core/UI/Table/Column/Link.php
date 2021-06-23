<?php

namespace Duxravel\Core\UI\Table\Column;

/**
 * Class Link
 */
class Link implements Component
{

    private array $link;

    /**
     * 添加条目
     * @param string $name
     * @param string $route
     * @param array $params
     * @return \Duxravel\Core\UI\Widget\Link
     */
    public function add(string $name, string $route = '', array $params = []): \Duxravel\Core\UI\Widget\Link
    {
        $link = new \Duxravel\Core\UI\Widget\Link($name, $route, $params);
        $this->link[] = $link;
        return $link;
    }


    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {
        $link = [];
        foreach ($this->link as $class) {
            $link[] = $class->render($data);
        }
        return implode('<span class="mx-1 text-gray-300"> | </span>', array_filter($link));
    }

}

<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Link
 */
class Link implements Component
{

    private array $link;
    private array $routes = [];

    /**
     * 添加条目
     * @param string $name
     * @param string $label
     * @return \Duxravel\Core\UI\Widget\Link
     */
    public function add(string $name, string $route, array $params = []): \Duxravel\Core\UI\Widget\Link
    {
        $label = $route . '?' . http_build_query($params);
        $link = new \Duxravel\Core\UI\Widget\Link($name, $label);
        $link = $link->model('rowData');
        $this->link[] = $link;
        $this->routes[$label] = [
            'route' => $route,
            'params' => $params
        ];
        return $link;
    }

    /**
     * 获取数据
     * @param $rowData
     * @return array
     */
    public function getData($rowData)
    {
        $urls = [];
        foreach ($this->routes as $key => $vo) {
            $params = [];
            foreach ($vo['params'] as $k => $v) {
                $params[$k] = Tools::parsingArrData($rowData, $v, true);
            }
            $urls[$key] = route($vo['route'], $params, false);
        }
        return $urls;
    }


    /**
     * @param $value
     * @param $data
     * @return array
     */
    public function render($label): array
    {
        $link = [];
        foreach ($this->link as $class) {
            $link[] = [
                'nodeName' => 'span',
                'child' => $class->render()
            ];
        }
        $link = array_filter($link);
        return [
            'nodeName' => 'div',
            'class' => 'inline-flex gap-4',
            'child' => $link
        ];
    }

}

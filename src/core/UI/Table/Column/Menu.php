<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Menu
 */
class Menu implements Component
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
        $link = new \Duxravel\Core\UI\Widget\Link($name, $route, $params);
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
        $options = [];
        foreach ($this->link as $key => $class) {
            $data = $class->render();

            $route = [
                'nodeName' => 'route',
                'type' => $data['type'],
                'title' => $data['title'],
                'href' => $class->getRoute(),
            ];

            $options[] = [
                'label' => $data['name'],
                'key' => $key,
                'route' =>$route,
            ];
        }
        $options = array_filter($options);

        return [
            'nodeName' => 'n-dropdown',
            'width' => '80',
            'placement' => 'right-start',
            'overlap' => true,
            'trigger' => 'click',
            'options' => $options,
            'render-label:option' => [
                'nodeName' => 'route',
                'class' => 'block',
                'vBind:href' => 'rowData[option.route.href]',
                'vBind:title' => 'option.route.title',
                'vBind:type' => 'option.route.type',
                'child' => '{{option.label}}'
            ],
            'child' => [
                'nodeName' => 'n-icon',
                'class' => 'cursor-pointer',
                'size' => 16,
                'child' => [
                    'nodeName' => 'dots-vertical-icon'
                ]
            ],
        ];
    }

}

<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Link
 */
class Link implements Component
{

    private array $link;
    private array $fields;
    private array $routes = [];

    /**
     * 设置数据列字段
     * @param array $fields
     * @return void
     */
    public function fields(Array $fields = [])
    {
        $this->fields = $fields;
        return $this;
    }

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
        $link->fields($this->fields);
        $link = $link->model('rowData.record');
        $this->link[] = $link;
        return $link;
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

            $type = $class->getType();
            $typeConfig = $class->getTypeConfig();

            $data = $class->render();

            if ($type === 'ajax' && (!$data['vBind:before'] || !$data['before'])) {
                $data['vBind:before'] = "() => rowData.record.__loading = true";
            }

            if ($type === 'ajax' && (!$data['vBind:after'] || !$data['after'])) {
                $data['vBind:after'] = "() => rowData.record.__loading = false";
            }

            $link[] = [
                'nodeName' => 'span',
                'child' => $data
            ];
        }

        $link = array_filter($link);
        return [
            'nodeName' => 'a-spin',
            'vBind:loading' => 'rowData.record.__loading',
            'child' => [
                'nodeName' => 'div',
                'class' => 'inline-flex gap-2',
                'child' => $link
            ]
        ];
    }

}

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

    /**
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields = []): Link
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * 添加条目
     * @param string $name
     * @param string $route
     * @param array  $params
     * @param bool   $absolute
     * @return \Duxravel\Core\UI\Widget\Link
     */
    public function add(string $name, string $route, array $params = [], bool $absolute = false): \Duxravel\Core\UI\Widget\Link
    {
        $link = new \Duxravel\Core\UI\Widget\Link($name, $route, $params, $absolute);
        $link->fields($this->fields);
        $link = $link->model('rowData.record');
        $this->link[] = $link;
        return $link;
    }

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {
        $link = [];
        foreach ($this->link as $class) {
            $type = $class->getType();
            $data = $class->render();
            if(empty($data)){
                continue;
            }

            if (($type === 'ajax' || $type === 'dialog') && (!$data['vBind:before'] || !$data['before'])) {
                $data['vBind:before'] = "() => rowData.record.__loading = true";
            }

            if (($type === 'ajax' || $type === 'dialog') && (!$data['vBind:after'] || !$data['after'])) {
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

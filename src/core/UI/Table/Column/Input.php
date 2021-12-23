<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Input
 */
class Input implements Component
{

    private string $route;
    private array $params = [];
    private string $field;
    private array $fields;
    private string $url;

    /**
     * Toggle constructor.
     * @param string $field
     * @param string|array $url
     * @param array $params
     */
    public function __construct(string $field, $url, array $params = [])
    {
        $this->field = $field;
        $this->params = $params;
        $this->route = $route;
    }


    /**
     * 设置数据列字段
     * @param array $fields
     * @return void
     */
    public function fields(array $fields = [])
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param $field
     * @return string
     */
    public function render($field): array
    {
        $url = app_route($this->route, $this->params, false, 'rowData.record', $this->fields);
        return [
            'nodeName' => 'n-input',
            'class' => 'shadow-sm',
            'vModel:value' => "rowData.record['$field']",
            'vOn:blur' => "editValue($url, {'field': '$this->field', '$this->field': rowData.record['$field']})",
        ];
    }

}

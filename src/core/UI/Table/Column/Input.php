<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Input
 */
class Input implements Component
{

    private string $route;
    private array $params;
    private string $field;
    private array $fields;

    /**
     * @param string $field
     * @param string $route
     * @param array  $params
     */
    public function __construct(string $field, string $route, array $params = [])
    {
        $this->field = $field;
        $this->params = $params;
        $this->route = $route;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields = []): Input
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {
        $url = app_route($this->route, $this->params, false, 'rowData.record', $this->fields);
        return [
            'nodeName' => 'n-input',
            'class' => 'shadow-sm',
            'vModel:value' => "rowData.record['$label']",
            'vOn:blur' => "editValue($url, {'field': '$this->field', '$this->field': rowData.record['$label']})",
        ];
    }

}

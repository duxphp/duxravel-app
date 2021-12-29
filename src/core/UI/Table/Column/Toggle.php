<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Toggle
 */
class Toggle implements Component
{

    private string $route;
    private array $params;
    private string $field;
    private array $fields;
    private string $url;

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
     * 设置列字段
     * @param array $fields
     * @return $this
     */
    public function fields(Array $fields = []): self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param $label
     * @return string[]
     */
    public function render($label): array
    {
        $url = app_route($this->route, $this->params, false, 'rowData.record', $this->fields);
        return [
            'nodeName' => 'a-switch',
            'vModel:model-value' => "rowData.record['$label']",
            'vOn:change' => "rowData.record['$label'] = \$event, editValue($url, {'field': '$this->field', '$this->field': rowData.record['$label']})",
        ];
    }

}

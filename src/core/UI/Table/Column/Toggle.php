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
    private $checkedValue = 1;
    private $uncheckedValue = 0;

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
     * 开关数据
     * @param string|number|boolean $checkedValue
     * @param string|number|boolean $uncheckedValue
     * @return $this
     */
    public function data($checkedValue,$uncheckedValue){
        $this->checkedValue = $checkedValue;
        $this->uncheckedValue = $uncheckedValue;
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
            'checkedValue' => $this->checkedValue,
            'uncheckedValue' => $this->uncheckedValue
        ];
    }

}

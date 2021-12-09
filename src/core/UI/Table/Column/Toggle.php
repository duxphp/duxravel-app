<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Toggle
 */
class Toggle implements Component
{

    private array $params;
    private string $field;
    private string $url;
    private string $label;

    /**
     * Toggle constructor.
     * @param string $url
     * @param array $params
     */
    public function __construct(string $field, string $url, array $params = [])
    {
        $this->field = $field;
        $this->url = $url;
        $this->params = $params;
        $this->label = $url . '?' . http_build_query($params);
    }

    /**
     * 获取数据
     * @param $rowData
     * @return array
     */
    public function getData($rowData, $field)
    {
        $params = [];
        foreach ($this->params as $key => $vo) {
            $params[$key] = Tools::parsingArrData($data, $vo, true);
        }
        return [
            $this->label => route($this->url, $this->params, false)
        ];
    }

    /**
     * @param $field
     * @return string
     */
    public function render($field): array
    {
        return [
            'nodeName' => 'n-switch',
            'vModel:value' => "rowData.record['$field']",
            'vOn:update:value' => "rowData.record['$field'] = \$event, editValue(rowData.record['$this->label'], {'field': '$this->field', '$this->field': rowData.record['$field']})",
        ];
    }

}

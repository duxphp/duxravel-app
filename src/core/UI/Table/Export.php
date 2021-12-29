<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\Util\Excel;
use Duxravel\Core\UI\Tools;

/**
 * 数据导出
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class Export
{
    public array $column = [];
    public string $title = '数据导出';
    public string $subtitle = '';

    /**
     * 标题
     * @param $name
     * @return $this
     */
    public function title($name): self
    {
        $this->title = $name;
        return $this;
    }

    /**
     * 副标题
     * @param $name
     * @return $this
     */
    public function subtitle($name): self
    {
        $this->subtitle = $name;
        return $this;
    }

    /**
     * 列设置
     * @param string $name
     * @param string $value
     * @param int    $width
     * @return $this
     */
    public function column(string $name, string $value, int $width = 10): self
    {
        $this->column[] = [
            'name' => $name,
            'value' => $value,
            'width' => $width
        ];
        return $this;
    }

    /**
     * 输出表单
     * @param $data
     */
    public function render($data): void
    {
        $header = [];
        $cellData = [];
        foreach ($data as $vo) {
            $tmp = [];
            foreach ($this->column as $column) {
                if (is_string($column['value'])) {
                    $tmp[] = Tools::parsingArrData($vo, $column['value']);
                } else {
                    $tmp[] = call_user_func($column['value'], $vo);
                }
            }
            $cellData[] = $tmp;
        }
        foreach ($this->column as $vo) {
            $header[] = [
                'name' => $vo['name'],
                'width' => $vo['width']
            ];
        }
        Excel::export($this->title, $this->subtitle, $header, $cellData);
    }

}

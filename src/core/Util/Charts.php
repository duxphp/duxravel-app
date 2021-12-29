<?php

namespace Duxravel\Core\Util;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * Class Charts
 * @package Duxravel\Core\Util
 */
class Charts
{
    private string $type = '';
    private ?\Closure $option = null;
    private int $height = 200;
    private $width = '100%';
    private bool $zoom = false;
    private bool $toolbar = false;
    private bool $datatime = false;
    private array $legend = [
        'status' => false,
        'x' => 'right',
        'y' => 'top'
    ];

    private array $date = [
        'start' => '',
        'stop' => '',
        'interval' => '1 days',
        'format' => 'Y-m-d'
    ];

    private array $series = [];
    private array $labels = [];
    private array $data = [];
    private array $title = [];
    private array $subtitle = [];


    /**
     * @param int|string $width
     * @return $this
     */
    public function width($width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param string $title
     * @param string $align
     * @return $this
     */
    public function title(string $title, string $align = 'left'): self
    {
        $this->title = [
            'title' => $title,
            'align' => $align
        ];
        return $this;
    }

    /**
     * @param string $title
     * @param string $align
     * @return $this
     */
    public function subtitle(string $title, string $align = 'left'): self
    {
        $this->subtitle = [
            'title' => $title,
            'align' => $align
        ];
        return $this;
    }

    /**
     * @param false $zoom
     * @return $this
     */
    public function zoom(bool $zoom = false): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * @param false $toolbar
     * @return $this
     */
    public function toolbar(bool $toolbar = false): self
    {
        $this->toolbar = $toolbar;
        return $this;
    }

    /**
     * @param        $status
     * @param string $x
     * @param string $y
     * @return $this
     */
    public function legend($status, string $x = "right", string $y = 'top'): self
    {
        $this->legend = [
            'status' => $status,
            'x' => $x,
            'y' => $y
        ];
        return $this;
    }

    /**
     * 时间轴
     * @param bool $status
     * @return $this
     */
    public function datetime(bool $status = true): self
    {
        $this->datatime = $status;
        return $this;

    }

    /**
     * @param string $start
     * @param string $stop
     * @param string $interval
     * @param string $format
     * @return $this
     */
    public function date(string $start, string $stop, string $interval = '1 days', string $format = 'Y-m-d'): self
    {
        $this->date = [
            'start' => $start,
            'stop' => $stop,
            'interval' => $interval,
            'format' => $format
        ];
        return $this;
    }

    /**
     * @param string $name
     * @param array  $data
     * @param string $format
     * @return $this
     */
    public function data(string $name, array $data = [], string $format = 'Ymd'): self
    {
        $this->data[] = [
            'name' => $name,
            'data' => $data,
            'format' => $format
        ];
        return $this;
    }


    /**
     * 线型图
     * @return $this
     */
    public function line(): self
    {
        $this->type = 'line';

        $this->option = function () {
            return [
                'dataLabels' => [
                    'enabled' => false,
                ],
                'fill' => [
                    'opacity' => 1,
                ],
                'stroke' => [
                    'curve' => "straight",
                ],
                'xaxis' => [
                    'type' => $this->datatime ? 'datetime' : 'category',
                    'categories' => $this->labels
                ],
            ];
        };
        return $this;
    }

    /**
     * 区域图
     * @return $this
     */
    public function area(): self
    {
        $this->type = 'area';

        $this->option = function () {
            return [
                'dataLabels' => [
                    'enabled' => false,
                ],
                'fill' => [
                    'opacity' => .2,
                    'type' => 'solid'
                ],
                'xaxis' => [
                    'type' => $this->datatime ? 'datetime' : 'category',
                    'categories' => $this->labels
                ],
            ];
        };
        return $this;
    }

    /**
     * 柱状图
     * @return $this
     */
    public function column(): self
    {
        $this->type = 'bar';

        $this->option = function () {
            return [
                'plotOptions' => [
                    'bar' => [
                        'columnWidth' => '50%',
                    ]
                ],
                'dataLabels' => [
                    'enabled' => false,
                ],
                'fill' => [
                    'opacity' => 1,
                ],
                'xaxis' => [
                    'type' => $this->datatime ? 'datetime' : 'category',
                    'categories' => $this->labels
                ],
            ];
        };
        return $this;
    }

    /**
     * @param bool $html
     * @return array|string
     */
    public function render(bool $html = false)
    {
        $this->renderData();

        $option = call_user_func($this->option);


        $option['chart'] = [
            'id' => 'vuechart-' . get_uuid(10),
        ];

        $option['grid'] = [
            'strokeDashArray' => 4,
        ];

        if ($this->title) {
            $option['title'] = [
                'text' => $this->title['title'],
                'align' => $this->title['align'],
                'style' => [
                    'fontSize' => '16px',
                    'fontWeight' => 'normal',
                ]
            ];
        }
        if ($this->subtitle) {
            $option['subtitle'] = [
                'text' => $this->title['title'],
                'align' => $this->title['align'],
                'style' => [
                    'fontSize' => '14px',
                    'fontWeight' => 'normal',
                ]
            ];
        }

        if ($this->toolbar) {
            $option['chart']['toolbar'] = [
                'show' => true,
                'autoSelected' => true
            ];
        } else {
            $option['chart']['toolbar'] = [
                'show' => false
            ];
        }

        if ($this->zoom) {
            $option['chart']['zoom'] = [
                'enabled' => true,
                'type' => 'x',
                'autoScaleYaxis' => false
            ];
        } else {
            $option['chart']['zoom'] = [
                'enabled' => false
            ];
        }

        if ($this->legend['status']) {
            $option['legend'] = [
                'show' => true,
                'position' => $this->legend['y'],
                'horizontalAlign' => $this->legend['x'],
                'floating' => true,
                'offsetY' => 0,
                'offsetX' => -5
            ];
        } else {
            $option['legend'] = [
                'show' => false
            ];
        }

        if ($html) {
            return $this->renderHtml($option);
        } else {
            return $this->renderNode($option);
        }

    }

    /**
     * @param array$option
     * @return string
     */
    private function renderHtml(array $option): string
    {
        $option = json_encode($option);
        $series = json_encode($this->series);
        return <<<HTML
            <apexchart
              ref="chart"
              width="$this->width"
              height="$this->height"
              type="$this->type"
              :options='$option'
              :series='$series'
            ></apexchart>
        HTML;
    }

    /**
     * @param array $option
     * @return array
     */
    private function renderNode(array $option): array
    {
        return [
            'nodeName' => 'apexchart',
            'ref' => 'chart',
            'width' => $this->width,
            'height' => $this->height,
            'type' => $this->type,
            'options' => $option,
            'series' => $this->series
        ];
    }

    /**
     * @return void
     */
    private function renderData(): void
    {
        $labels = $this->getDateFromRange($this->date['start'] ?: date($this->data['format'], strtotime('-7 day')), $this->date['stop'] ?: date($this->data['format']), $this->date['interval'], $this->date['format']);
        $this->labels = $labels;

        foreach ($this->data as $data) {
            $group = [];
            foreach ($data['data'] as $vo) {
                $vo['label'] = date_format(date_create_from_format($data['format'], $vo['label']), $this->date['format']);
                $group[$vo['label']] += $vo['value'];
            }
            $tmpArr = [];
            foreach ($labels as $label) {
                $tmpArr[] = $group[$label] ?: 0;
            }
            $this->series[] = [
                'name' => $data['name'],
                'data' => $tmpArr
            ];
        }

    }

    /**
     * @param string $startdate
     * @param string $enddate
     * @param string $interval
     * @param string $format
     * @return array
     */
    private function getDateFromRange(string $startdate, string $enddate, $interval = '1 days', $format = 'Y-m-d'): array
    {
        $period = CarbonPeriod::create($startdate, $interval, $enddate)->toArray();
        $data = [];
        foreach ($period as $date) {
            $data[] = $date->format($format);
        }
        return $data;
    }

    /**
     * @param $method
     * @param $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $this->type = $method;
        $this->option = function () use ($arguments) {
            $option = [
                'dataLabels' => [
                    'enabled' => false,
                ],
            ];

            if ($arguments && $arguments[0] instanceof \Closure) {
                call_user_func($arguments[0], $option, $this);
            }
        };
        return $this;
    }
}

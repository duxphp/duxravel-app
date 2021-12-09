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
    private $type = '';
    private ?\Closure $option = null;
    private $height = 200;
    private $width = '100%';
    private $zoom = false;
    private $toolbar = false;
    private $legend = [
        'status' => false,
        'x' => 'right',
        'y' => 'top'
    ];

    private $date = [
        'start' => '',
        'stop' => '',
        'interval' => '1 days',
        'format' => 'Y-m-d'
    ];

    private $series = [];
    private $labels = [];
    private $data = [];
    private $title = [];
    private $subtitle = [];


    /**
     * @param $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param $height
     * @return $this
     */
    public function height($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param $title
     * @param string $align
     * @return $this
     */
    public function title($title, $align = 'left')
    {
        $this->title = [
            'title' => $title,
            'align' => $align
        ];
        return $this;
    }

    /**
     * @param $title
     * @param string $align
     * @return $this
     */
    public function subtitle($title, $align = 'left')
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
    public function zoom($zoom = false)
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * @param false $toolbar
     * @return $this
     */
    public function toolbar($toolbar = false)
    {
        $this->toolbar = $toolbar;
        return $this;
    }

    /**
     * @param $status
     * @param string $x
     * @param string $y
     * @return $this
     */
    public function legend($status, $x = "right", $y = 'top')
    {
        $this->legend = [
            'status' => $status,
            'x' => $x,
            'y' => $y
        ];
        return $this;
    }

    /**
     * @param $start
     * @param $stop
     * @param string $interval
     * @param string $format
     * @return $this
     */
    public function date($start, $stop, $interval = '1 days', $format = 'Y-m-d')
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
     * @param array $data
     * @return $this
     */
    public function data(string $name, array $data = [])
    {
        $this->data[] = [
            'name' => $name,
            'data' => $data
        ];
        return $this;
    }

    /**
     * 线型图
     * @return $this
     */
    public function line()
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
    public function area()
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
    public function column()
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
                    'categories' => $this->labels
                ],
            ];
        };
        return $this;
    }

    /**
     * @return string
     */
    public function render($html = false)
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
            $option['chart']['zoom'] = [
                'show' => true
            ];
        } else {
            $option['chart']['toolbar'] = [
                'show' => false
            ];
        }

        if ($this->zoom) {
            $option['chart']['zoom'] = [
                'enabled' => true
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

    private function renderHtml($option)
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

    private function renderNode($option)
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
     * @return $this
     */
    private function renderData()
    {
        $series = [];
        $labels = [];

        $group = [];
        $names = [];

        $labels = $this->getDateFromRange($this->date['start'] ?: date($this->data['format'], strtotime('-7 day')), $this->date['stop'] ?: date($this->data['format']), $this->date['interval'], $this->date['format']);
        $this->labels = $labels;

        foreach ($this->data as $data) {
            $group = [];
            foreach ($data['data'] as $vo) {
                $vo['label'] = Carbon::create($vo['label'])->format($this->date['format']);
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

        return $this;
    }

    private function getDateFromRange($startdate, $enddate, $interval = '1 days', $format = 'Y-m-d'): array
    {
        $period = CarbonPeriod::create($startdate, $interval, $enddate)->toArray();
        $data = [];
        foreach ($period as $date) {
            $data[] = $date->format($format);
        }
        return $data;
    }

    /**
     * 自动方法类
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws \Exception
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
                $option = call_user_func($arguments[0], $option, $this);
            }
        };
        return $this;
    }
}

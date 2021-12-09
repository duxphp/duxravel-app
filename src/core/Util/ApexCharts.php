<?php

namespace Duxravel\Core\Util;

use ArielMejiaDev\LarapexCharts\LarapexChart;
/**
 * Class ApexCharts
 * @package Duxravel\Core\Util
 */
class ApexCharts
{
    /**
     * 数据对象
     * @var Charts\ApexData
     */
    private $apexData;

    /**
     * 原始数据
     * @var string
     */
    private $type = '';

    /**
     * 高度
     * @var int
     */
    private $height = 300;

    /**
     * 图标配置
     * @var array
     */
    private $config = [];

    /**
     * 颜色配置
     * @var string[]
     */
    private $color = [
        '#005dff',
        '#d5e1ed',
        '#00d586'
    ];

    /**
     * 语言配置
     * @var array[]
     */
    private $lang = [[
        "name" => "zh-CN",
        "options" => [
            "months" => ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            "shortMonths" => ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            "days" => ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
            "shortDays" => ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
            "toolbar" => [
                "exportToSVG" => "下载 SVG",
                "exportToPNG" => "下载 PNG",
                "menu" => "菜单",
                "selection" => "选择",
                "selectionZoom" => "选择大小",
                "zoomIn" => "放大",
                "zoomOut" => "缩小",
                "pan" => "移动",
                "reset" => "重置"
            ]
        ]
    ]];

    private $chartObj;
    private $chart;


    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    /**
     * 区域图表
     * @param array $data
     * @return $this
     */
    public function area(array $data)
    {
        $this->type = 'area';
        $this->data = $data;
        $config = [
            'chart' => [
                'locales' => $this->lang,
                'type' => "area",
                'fontFamily' => 'inherit',
                'defaultLocale' => 'zh-CN',

                'sparkline' => [
                    'enabled' => false // 隐藏轴线
                ],
                'animations' => [
                    'enabled' => true
                ],
                'zoom' => [
                    'enabled' => false
                ],
                'toolbar' => [
                    'show' => false
                ]
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'fill' => [
                'opacity' => .2,
                'type' => 'solid'
            ],
            'grid' => [
                'strokeDashArray' => 4,
            ],
            'xaxis' => [
                'labels' => [
                    'padding' => 0
                ],
                'tooltip' => [
                    'enabled' => false
                ],
                'axisBorder' => [
                    'show' => false,
                ],
                'type' => 'datetime',
            ],
            'yaxis' => [
                'labels' => [
                    'padding' => 4
                ],
            ],
            'series' => [],
            'labels' => [],
            'colors' => $this->color,
            'legend' => [
                'show' => true,
                'position' => 'top',
                'horizontalAlign' =>'right',
                'floating' => true,
                'offsetY' => 0,
                'offsetX' => -5
            ],
        ];

        $this->config = $config;
        return $this;
    }


    /**
     * 线型图表
     * @param array $data
     * @return $this
     */
    public function line(array $data): ApexCharts
    {
        $this->type = 'line';
        $this->data = $data;
        $config = [
            'chart' => [
                'locales' => $this->lang,
                'type' => "line",
                'fontFamily' => 'inherit',
                'defaultLocale' => 'zh-CN',
                'height' => 200,
                'sparkline' => [
                    'enabled' => false
                ],
                'animations' => [
                    'enabled' => true
                ],
                'zoom' => [
                    'enabled' => false
                ],

                'toolbar' => [
                    'show' => false
                ]
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'fill' => [
                'opacity' => 1,
            ],
            'stroke' => [
                'curve' => "straight",
            ],
            'grid' => [
                'strokeDashArray' => 4,
            ],
            'xaxis' => [
                'labels' => [
                    'padding' => 0
                ],
                'tooltip' => [
                    'enabled' => false
                ],
                'type' => 'datetime',
            ],
            'yaxis' => [
                'labels' => [
                    'padding' => 4
                ],
            ],
            'series' => [],
            'labels' => [],
            'colors' => $this->color,
            'legend' => [
                'show' => true,
                'position' => 'top',
                'horizontalAlign' =>'right',
                'floating' => true,
                'offsetY' => 0,
                'offsetX' => -5
            ]
        ];

        $this->config = $config;
        return $this;
    }

    /**
     * 柱状图表
     * @param array $data
     * @return $this
     */
    public function bar(array $data): ApexCharts
    {
        $this->type = 'bar';
        $this->data = $data;

        $this->chartObj = $this->chart->barChart()->setGrid(true)->setTitle('');


        return $this;

        $config = [
            'chart' => [
                'locales' => $this->lang,
                'type' => "bar",
                'fontFamily' => 'inherit',
                'defaultLocale' => 'zh-CN',

                'sparkline' => [
                    'enabled' => false
                ],
                'animations' => [
                    'enabled' => false
                ],
                'zoom' => [
                    'enabled' => false
                ],
                'toolbar' => [
                    'show' => false
                ]
            ],
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
            'grid' => [
                'strokeDashArray' => 4,
            ],
            'xaxis' => [
                'labels' => [
                    'padding' => 0
                ],
                'tooltip' => [
                    'enabled' => false
                ],
                'axisBorder' => [
                    'show' => false,
                ],
                'type' => 'datetime',
            ],
            'yaxis' => [
                'labels' => [
                    'padding' => 4
                ],
            ],
            'series' => [],
            'labels' => [],
            'colors' => $this->color,
            'legend' => [
                'show' => true,
                'position' => 'top',
                'horizontalAlign' =>'right',
                'floating' => true,
                'offsetY' => 0,
                'offsetX' => -5
            ]
        ];

        $this->config = $config;
        return $this;
    }

    /**
     * 热力图
     * @param array $data
     * @return $this
     */
    public function heatmap(array $data): ApexCharts
    {
        $this->type = 'heatmap';
        $this->data = $data;
        $config = [
            'chart' => [
                'locales' => $this->lang,
                'type' => "heatmap",
                'defaultLocale' => 'zh-CN',
                'height' => 400,
                'toolbar' => [
                    'show' => false
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'colors' => $this->color,
            'xaxis' => [
                'type' => 'category',
            ],
            'series' => [],
            'legend' => [
                'show' => false,
            ]
        ];
        $this->config = $config;
        return $this;

    }

    /**
     * 数据类型
     * @param string $type
     * @param array $config
     * @return $this
     */
    public function type(string $type = 'day', array $config = []): ApexCharts
    {
        $this->apexData = new Charts\ApexData($type, $config);
        return $this;
    }



    public function height($height)
    {
        $this->height = $height;
        return $this;

    }

    /**
     * 渲染图表
     * @param string $id
     * @param array $config
     */
    public function render(string $id, callable $callback = null)
    {

        $chat = $this->chartObj;

        list($labels, $series) = $this->apexData->data($this->data);

        foreach ($series as $item) {
            $chart = $chat->addData($item['name'] ?: '', $item['data']);
        }
        $chart = $chat->setLabels($labels);

        $config = $chart->setHeight($this->height)->toVue();

        $config['options']['tooltip']['theme'] = 'window.derkMode ? "dark" : ""';
        $option = json_encode($config['options']);
        $series = json_encode($config['series'] ?: []);
        return <<<HTML
            <apexchart
              width="{$config['width']}"
              height="{$config['height']}"
              type="{$config['type']}"
              :options='{$option}'
              :series='{$series}'
            ></apexchart>
            HTML;
    }
}

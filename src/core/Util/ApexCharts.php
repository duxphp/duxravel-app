<?php

namespace Duxravel\Core\Util;
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
     * @var array
     */
    private $data = [];

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

    /**
     * 渲染图表
     * @param string $id
     * @param array $config
     * @return string
     */
    public function render(string $id, callable $callback = null): string
    {
        list($labels, $series) = $this->apexData->data($this->data);
        $this->config['labels'] = $labels;
        $this->config['series'] = $series;
        $config = $this->config;
        if(is_callable($callback)) {
            $config = call_user_func($callback, $config);
        }
        $config = json_encode($config);
        return <<<HTML
            <script>
            Do('chart', function () {
                window['chart-$id'] = new ApexCharts(document.querySelector('#$id'), $config);
                window['chart-$id'].render()
            })
            </script>
            HTML;
    }

    /**
     * 区域图表
     * @param array $data
     * @return $this
     */
    public function area(array $data)
    {
        $this->data = $data;
        $config = [
            'chart' => [
                'locales' => $this->lang,
                'type' => "area",
                'fontFamily' => 'inherit',
                'defaultLocale' => 'zh-CN',
                'height' => 100,
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
        $this->data = $data;
        $config = [
            'chart' => [
                'locales' => $this->lang,
                'type' => "bar",
                'fontFamily' => 'inherit',
                'defaultLocale' => 'zh-CN',
                'height' => 100,
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
}

<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Chart
 */
class Chart implements Component
{

    private int $day;
    private string $has;
    private string $key;
    private string $name;
    private string $type;

    /**
     * Chart constructor.
     * @param int $day
     * @param string $has
     * @param string $key
     * @param string $name
     * @param string $type
     */
    public function __construct(int $day = 7, string $has = 'viewsData', string $key = 'pv', string $name = '访问量', string $type = 'area')
    {
        $this->day = $day;
        $this->has = $has;
        $this->key = $key;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {
        $chartData = $data->{$this->has};
        $tmpData = [];
        $chartData->each(function ($item) use (&$tmpData) {
            $tmpData[$item->date] += $item[$this->key];
        });
        $tmpChart = [];
        foreach ($tmpData as $key => $vo) {
            $tmpChart[] = [
                "value" => $vo,
                "label" => $key,
                "name" => $this->name
            ];
        }
        $viewId = 'chart-chart-' . \Str::random(5);

        $chart = (new \Duxravel\Core\Util\ApexCharts)->{$this->type}($tmpChart)->type('day', [
            'start' => date('Y-m-d', strtotime('-' . $this->day . ' day')),
        ])->render($viewId, function ($config) {
            \Arr::set($config, 'chart.height', 35);
            \Arr::set($config, 'chart.sparkline.enabled', true);
            \Arr::set($config, 'chart.animations.enabled', false);
            \Arr::set($config, 'tooltip.enabled', false);
            return $config;
        });

        return "<div id='$viewId' class='p-1'></div> $chart";

    }

}

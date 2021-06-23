<?php

namespace Duxravel\Core\Util\Charts;

class ApexData
{
    public $type;
    public $config;

    public function __construct(string $type = 'day', array $config = [])
    {
        $this->type = $type;
        $this->config = $config;
    }

    /**
     * @param $startdate
     * @param $enddate
     * @return array
     */
    private function getDateFromRange($startdate, $enddate): array
    {
        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);
        // 计算日期段内有多少天
        $days = ($etimestamp - $stimestamp) / 86400 + 1;
        // 保存每天日期
        $date = array();
        for ($i = 0; $i < $days; $i++) {
            $date[] = date('Y-m-d', $stimestamp + (86400 * $i));
        }
        return $date;
    }

    public function data($data): array
    {
        $series = [];
        $labels = [];

        $group = [];
        $names = [];
        foreach ($data as $vo) {
            if ($this->type === 'day') {
                $vo['label'] = date('Y-m-d', strtotime($vo['label']));
            }
            $group[$vo['name']][$vo['label']] += $vo['value'];
            $labels[] = $vo['label'];
            $names[] = $vo['name'];
        }
        $labels = array_unique($labels);

        if ($this->type === 'day') {
            $labels = $this->getDateFromRange($this->config['start'] ?? $labels[0], $this->config['stop'] ?? end($labels));
        }

        $date = array();
        foreach ($labels as $key => $vo) {
            $date[] = strtotime($vo);
        }
        array_multisort($date, SORT_ASC, $labels);

        $names = array_unique($names);
        $tmpArr = [];
        foreach ($names as $name) {
            foreach ($labels as $label) {
                $tmpArr[$name][] = $group[$name][$label] ?: 0;
            }
        }
        foreach ($tmpArr as $name => $vo) {
            $series[] = [
                'name' => $name,
                'data' => $vo
            ];
        }
        return [$labels, $series];
    }
}

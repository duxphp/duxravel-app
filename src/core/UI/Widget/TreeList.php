<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class TreeList
 * @package Duxravel\Core\UI\Widget
 */
class TreeList extends Widget
{

    private $key;
    private $field;
    private $url = null;
    private $sortUrl = null;
    private $filter = null;
    private $search = true;
    private $keyword = [];
    private $expand = true;
    private $menu = [];
    private $menuLevel = [];
    private $labelNode = [];

    public function __construct($default, $field = '', $event = '')
    {
        $this->key = $default;
        $this->field = $field;
        $this->event = $event;
    }

    public function search(bool $bool = true, array $keyword = [])
    {
        $this->search = $bool;
        $this->keyword = $keyword;
        return $this;
    }

    public function menu($data = [], $level = [])
    {
        $this->menu = $data;
        $this->menuLevel = $level;
        return $this;
    }

    public function url(string $url = null)
    {
        $this->url = $url;
        return $this;
    }

    public function sortUrl(string $url = null)
    {
        $this->sortUrl = $url;
        return $this;
    }

    public function expand(bool $bool = true)
    {
        $this->expand = $bool;
        return $this;
    }

    public function filter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function label($node)
    {
        $this->labelNode = $node;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $urlPaths = parse_url(substr($this->url, 0, strrpos($this->url, "/")));
        $tree = [
            'nodeName' => 'widget-tree',
            'url' => $this->url,
            'sortUrl' => $this->sortUrl,
            'search' => $this->search,
            'keywords' => $this->keyword,
            'requestEventName' => $this->event ?: url_class($this->url)['class'],
            'vBind:filter' => $this->filter ?: [],
            'refreshUrls' => [trim($urlPaths['path'], '/')],
            'iconColor' => ['blue', 'cyan', 'green', 'orange', 'red', 'purple'],
            'vModel:value' => "data.filter['{$this->field}']",
        ];

        if ($this->labelNode) {
            $tree['child'] = [
                'nodeName' => 'span',
                'vSlot:label' => 'item',
                'child' => $this->labelNode
            ];
        }

        $menu = [];
        if ($this->menu) {
            foreach ($this->menu as $key => $vo) {
                $url = $vo['url'];
                $event = $vo['event'];
                $tmp = [
                    'text' => $vo['name'],
                ];
                $tmp['key'] = $key;
                if ($event) {
                    $tmp['event'] = $event;
                } else {
                    switch ($vo['type']) {
                        case 'dialog':
                            $tmp['event'] = $url ? "window.router.dialog($url)" : "window.dialog.alert({content: '未定义链接数据'})";
                            break;
                        case 'ajax':
                            $tmp['event'] = $url ? "window.router.ajax($url, {_method: 'POST', _title: '确认进行{$vo['name']}操作？'})" : "window.dialog.alert({content: '未定义链接数据'})";
                            break;
                        default:
                            $tmp['event'] = $url ? "window.router.push($url)" : "window.dialog.alert({content: '未定义链接数据'})";
                    }
                }
                $menu[] = $tmp;
            }
            $tree['contextMenus'] = $menu;
        }

        return $tree;
    }
}

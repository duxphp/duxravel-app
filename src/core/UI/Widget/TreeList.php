<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class TreeList
 * @package Duxravel\Core\UI\Widget
 */
class TreeList extends Widget
{

    private $key;
    private string $field;
    private string $event;
    private ?string $url = null;
    private ?string $sortUrl = null;
    private ?array $filter = null;
    private bool $search = true;
    private array $keyword = [];
    private array $menu = [];
    private array $labelNode = [];

    /**
     * @param        $default
     * @param string $field
     * @param string $event
     */
    public function __construct($default, string $field = '', string $event = '')
    {
        $this->key = $default;
        $this->field = $field;
        $this->event = $event;
    }

    /**
     * @param bool  $bool
     * @param array $keyword
     * @return $this
     */
    public function search(bool $bool = true, array $keyword = []): self
    {
        $this->search = $bool;
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function menu(array $data = []): self
    {
        $this->menu = $data;
        return $this;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function url(string $url = null): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function sortUrl(string $url = null): self
    {
        $this->sortUrl = $url;
        return $this;
    }

    /**
     * @param array $filter
     * @return $this
     */
    public function filter(array $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    public function label($node): TreeList
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

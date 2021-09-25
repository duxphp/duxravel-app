<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class TreeList
 * @package Duxravel\Core\UI\Widget
 */
class TreeList extends Widget
{

    private $key;
    private $url = null;
    private $sortUrl = null;
    private $search = true;
    private $expand = true;
    private $menu = [];
    private $menuLevel = [];

    public function __construct($default, $field = '')
    {
        $this->key = $default;
        $this->field = $field;
    }

    public function search(bool $bool = true)
    {
        $this->search = $bool;
        return $this;
    }

    public function menu($data = [], $level = [])
    {
        /* 'add' => [
            'name' => '新增',
            'route' => '',
            'params' => [],
            'type' => 'dialog',
        ] */

        $this->menu = $data;
        /* [
            ['add', 'edit', 'del']
        ] */
        $this->menuLevel = $level;
        return $this;
    }

    public function url(String $url = null)
    {
        $this->url = $url;
        return $this;
    }

    public function sortUrl(String $url = null)
    {
        $this->sortUrl = $url;
        return $this;
    }

    public function expand(bool $bool = true)
    {
        $this->expand = $bool;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $urlPaths = parse_url(substr($this->url,0,strrpos($this->url,"/")));



        $tree = [
            'nodeName' => 'widget-tree',
            'url' => $this->url,
            'sort-url' => $this->sortUrl,
            'url-bind' => false,
            'class' => 'overflow-x-auto app-scrollbar',
            'close-dialog-refresh-urls' => [trim($urlPaths['path'], '/')],
            'columns' => [
                [
                    'key' => 'name',
                    'title' => ''
                ]
            ],
            'child' => [
                [
                    'vSlot:default' => '{data: treeData, renderLabel, onDrop}',
                    'nodeName' => 'n-tree-copy',
                    'vBind:data' => 'treeData',
                    'vBind:renderLabel' => 'renderLabel',
                    'vBind:onDrop' => 'onDrop',
                    'level-mark-color' => ['#0087FF', '#30CCF6', '#49CD5B', '#FAAC11', '#F53739', '#AA69F6'],
                    'vBind:pattern' => 'data.search',
                    'block-line' => true,
                    'default-selected-keys' => [intval($this->key)],
                    'default-expand-all' => $this->expand,
                    'draggable' => true,
                    'context-menus' => [],
                    'context-level-menus' => $this->menuLevel,
                    'vModel:selected-keys' => "data.filter['{$this->field}']",
                    'render-label:info' => [
                        'nodeName' => 'div',
                        'class' => 'whitespace-nowrap',
                        'child' => '{{info.option.label}}'
                    ]
                ],
                [
                    'vSlot:empty' => '',
                    'nodeName' => 'div',
                    'class' => 'flex flex-col gap-2 justify-center items-center text-gray-300',
                    'child' => [
                        (new Icon('filter'))->size(42)->class('cursor-pointer')->getRender(),
                        [
                            'nodeName' => 'div',
                            'child' => '暂无数据'
                        ],
                    ],
                ]
            ]
        ];
        $menu = [];
        if ($this->menu) {
            foreach ($this->menu as $key => $vo) {
                $label = $vo['route'] . '?' . http_build_query($vo['params'] ?: []);
                $menu[$key] = [
                    'text' => $vo['name'],
                    'event' => $key,
                ];
                switch ($vo['type']) {
                    case 'dialog':
                        $tree['child'][0]['vOn:' . $key] = "\$event.rawNode['$label'] ? window.router.dialog(\$event.rawNode['$label']) : window.appDialog.alert({content: '未定义链接数据'})";
                        break;
                    case 'ajax':
                        $tree['child'][0]['vOn:' . $key] = "\$event.rawNode['$label'] ? window.router.ajax(\$event.rawNode['$label'], {_method: 'POST', _title: '确认进行该操作？'}) : window.appDialog.alert({content: '未定义链接数据'})";
                        break;
                    default:
                    $tree['child'][0]['vOn:' . $key] = "\$event.rawNode['$label'] ? window.router.push(\$event.rawNode['$label']) : window.router.push(\$event.rawNode['".route($vo['route'], $vo['params'])."'])";
                }
            }
            $tree['child'][0]['context-menus'] = $menu;
        }



        $search = [];
        if ($this->search) {
            $search = [
                'nodeName' => 'div',
                'class' => 'p-2 mb-2',
                'child' => [
                    'nodeName' => 'n-input',
                    'placeholder' => '搜索',
                    'vModel:value' => 'data.search',
                    'round' => true,
                    'child' => [
                        'vSlot:prefix' => '',
                        'nodeName' => 'n-icon',
                        'child' => [
                            'nodeName' => 'search-icon'
                        ]
                    ],
                ],
            ];
        }
        return [
            'nodeName' => 'div',
            'class' => 'p-2',
            'child' => [
                $search,
                $tree
            ]
        ];
    }
}

<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Icon;

/**
 * 表格节点
 * @package Duxravel\Core\UI\Table
 */
class Node
{
    private string $url = '';
    private ?string $key = '';
    private ?string $title = '';
    private bool $dialog = false;
    private bool $tree = false;
    private string $sortable = '';
    private array $params = [];
    private array $data = [];
    private array $columns = [];
    private array $filter = [];
    private array $quickFilter = [];
    private array $showFilter = [];
    private array $action = [];
    private array $bath = [];
    private array $type = [];
    private array $side = [];
    private array $header = [];
    private array $footer = [];
    private array $script = [];

    /**
     * Node constructor.
     * @param string $url
     * @param string $method
     * @param string|null $title
     */
    public function __construct(string $url, string $key, ?string $title = '')
    {
        $this->url = $url;
        $this->key = $key;
        $this->title = $title;
    }

    /**
     * @param $params
     * @return $this
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function dialog(bool $bool)
    {
        $this->dialog = $bool;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function data(array $filter, $show)
    {
        $this->data['filter'] = $filter;
        $this->data['show'] = $show;
        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function script($content)
    {
        if ($content instanceof \Closure) {
            $this->script[] = $content();
        } else {
            $this->script[] = $content;
        }
        return $this;
    }

    public function tree($config)
    {
        $this->tree = $config;
        return $this;
    }

    public function sortable($bool)
    {
        $this->sortable = $bool;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function columns(array $node)
    {
        $this->columns = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function type(array $node)
    {
        $this->type = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function filter(array $node)
    {
        $this->filter = array_values(array_filter($node));
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function quickFilter(array $node)
    {
        if ($node) {
            $this->quickFilter = $node;
        }
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function showFilter(array $node)
    {
        $this->showFilter = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function action(array $node)
    {
        $this->action = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function bath(array $node)
    {
        array_unshift($this->columns, [
            'type' => 'selection'
        ]);
        $this->params['vBind:row-key'] = "row => row.$this->key";
        $this->bath = $node;
        return $this;
    }

    /**
     * @param $node
     * @param string $type
     * @return $this
     */
    public function side($node, $type = 'left')
    {
        $this->side[$type] = is_callable($node) ? $node() : $node;
        return $this;
    }

    /**
     * @param $node
     */
    public function header($node)
    {
        $this->header = $node;
    }

    /**
     * @param $node
     */
    public function footer($node)
    {
        $this->footer = $node;
    }

    /**
     * @return array
     */
    private function showNode()
    {
        $node = [];
        foreach ($this->data['show'] as $key => $vo) {
            $node[] = [
                'nodeName' => 'n-tag',
                'closable' => true,
                'type' => 'success',
                'vIf' => "data.show['$key']",
                'vOn:close' => "data.show['$key'] = null, data.filter['$key'] = null",
                'child' => [
                    'nodeName' => 'div',
                    'class' => 'max-w-xs truncate',
                    'child' => "{{data.show['$key']}}"
                ]
            ];
        }
        return $node;
    }

    /**
     * @return array[]
     */
    private function headNode()
    {

        if ($this->filter) {
            $this->quickFilter[] = [
                'nodeName' => 'n-popover',
                'placement' => 'bottom-end',
                'trigger' => 'click',
                'displayDirective' => 'show',
                'width' => 300,
                'class' => 'filter-popover hidden',
                'vBind:ref' => <<<JS
                            el => dataRef.value = el
                            JS,
                'child' => [
                    [
                        'vSlot:trigger' => '',
                        'nodeName' => 'n-button',
                        'type' => 'default',
                        'attr-type' => 'button',
                        'class' => 'shadow-sm bg-white',
                        'child' => [
                            '筛选',
                            [
                                'vSlot:icon' => '',
                                'nodeName' => 'n-icon',
                                'child' => [
                                    'nodeName' => 'filter-icon'
                                ]
                            ]
                        ]
                    ],
                    [
                        'nodeName' => 'div',
                        'class' => 'flex flex-col',
                        'child' => $this->filter
                    ]
                ]
            ];
        }

        if ($this->type) {
            $header = [
                'nodeName' => 'n-radio-group',
                'name' => 'type',
                'size' => 'medium',
                'vModel:value' => 'data.filter.type',
                'child' => $this->type
            ];
        } else {
            $header = [
                'nodeName' => 'div',
                'class' => 'text-base',
                'child' => $this->title ?: '列表数据'
            ];
        }

        return [
            [
                'nodeName' => 'div',
                'class' => 'flex-grow',
                'child' => $header
            ],
            [
                'nodeName' => 'div',
                'class' => 'flex-none flex gap-4',
                'child' => [
                    [
                        'nodeName' => 'div',
                        'class' => 'flex gap-2',
                        'child' => $this->quickFilter
                    ],
                    [
                        'nodeName' => 'div',
                        'class' => 'flex gap-2',
                        'child' => $this->action
                    ]
                ]
            ]
        ];
    }

    public function sortableNode()
    {
        return [
            'nodeName' => 'div',
            'class' => '',
            'child' => [
                'nodeName' => 'app-tree',
                'n-params' => $this->params,
                'url' => $this->url,
                'sortUrl' => $this->sortable,
                'columns' => $this->columns,
                'vBind:filter' => 'data.filter',
            ]
        ];
    }

    /**
     * @return array
     */
    public function tableNode()
    {
        $this->params['bottom-bordered'] = false;
        return [
            'nodeName' => 'app-table',
            'url' => $this->url,
            'urlBind' => true,
            'n-params' => $this->params,
            'columns' => $this->columns,
            'vBind:filter' => 'data.filter',
            'page-prefix' => $this->bath,
            'child' => [
                [
                    'vSlot:empty' => '',
                    'nodeName' => 'app-empty',
                    'title' => '暂未找到数据',
                    'content' => '暂时未找到数据，您可以尝试重新加载'
                    
                ]
            ]
        ];
    }


    /**
     * @return array
     */
    public function render()
    {
        $this->script[] = <<<JS
            const dataRef = {}
            setTimeout(() => {
                if (dataRef.value) {
                    dataRef.value.setShow(true)
                    setTimeout(() => {
                        const filter = document.querySelector('.filter-popover')
                        setTimeout(() => {
                            if (filter) {
                                filter.classList.remove('hidden')
                            }
                        }, 300);
                    dataRef.value.setShow(false)
                    }, 50)
                }
            }, 50)
            return {dataRef}
        JS;
        return [
            'node' => [
                'nodeName' => 'app-form',
                'value' => [
                    'filter' => $this->data['filter'] ?: [],
                    'show' => $this->data['show'] ?: []
                ],
                'child' => [
                    'nodeName' => 'div',
                    'class' => 'flex lg:h-screen',
                    'vSlot' => '{value: data}',
                    'child' => [
                        $this->side['left'] ? [
                            'nodeName' => 'n-layout-content',
                            'class' => 'border-r border-gray-200 flex-none bg-white',
                            'native-scrollbar' => false,
                            'child' => $this->side['left']
                        ] : [],
                        [
                            'nodeName' => 'div',
                            'class' => 'flex-grow flex flex-col',
                            'child' => [
                                [
                                    'nodeName' => 'div',
                                    'class' => 'flex-none flex items-center p-4 border-b border-gray-300 bg-white shadow-sm z-10',
                                    'child' => $this->headNode()
                                ],
                                [
                                    'nodeName' => 'n-layout-content',
                                    'class' => 'flex-grow bg-gray-100',
                                    'native-scrollbar' => false,
                                    'child' => [
                                        'nodeName' => 'div',
                                        'class' => 'p-5',
                                        'child' => [
                                            $this->header,
                                            [
                                                'nodeName' => 'div',
                                                'class' => 'flex gap-2',
                                                'child' => [
                                                    $this->type ? [
                                                        'nodeName' => 'div',
                                                        'class' => 'text-lg mb-4 flex-grow',
                                                        'child' => $this->title
                                                    ] : [],
                                                    [
                                                        'nodeName' => 'div',
                                                        'class' => 'flex-none flex gap-2 mb-2',
                                                        'child' => $this->showNode()
                                                    ]
                                                ],
                                            ],
                                            $this->sortable ? $this->sortableNode() : $this->tableNode(),
                                            $this->footer,
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        $this->side['right'] ? [
                            'nodeName' => 'n-layout-content',
                            'class' => 'border-l border-gray-200 flex-none bg-white',
                            'native-scrollbar' => false,
                            'child' => $this->side['right']
                        ] : [],
                    ]
                ],
            ],
            'setupScript' => implode("\n", $this->script)
        ];
    }

}

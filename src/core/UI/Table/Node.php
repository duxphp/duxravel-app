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
    private bool $back = false;
    private string $mode = 'table';
    private string $sortable = '';
    private string $class = '';
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
    private array $scriptReturn = [];

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
     * 表格模式
     * @param string $mode
     * @return $this
     */
    public function mode($mode = 'table')
    {
        $this->mode = $mode;
        if ($mode === 'list') {
            $this->class .= 'table-list';
        }
        if ($mode === 'list-nohead') {
            $this->class .= 'table-list table-nohead';
        }
        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function class($class)
    {
        $this->class .= ' ' . $class;
        return $this;
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
    public function script($content, $return)
    {
        if ($content instanceof \Closure) {
            $this->script[] = $content();
        } else {
            $this->script[] = $content;
        }
        $this->scriptReturn[] = $return;
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

    public function back($bool = true)
    {
        $this->back = $bool;
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
     * @param string $type
     * @return $this
     */
    public function page($node, $type = 'left')
    {
        $this->page[$type] = is_callable($node) ? $node() : $node;
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
                'nodeName' => 'flex-grow',
                'child' => [
                    $this->back ? [
                        'nodeName' => 'route',
                        'type' => 'back',
                        'class' => 'text-xs items-center text-gray-500 hidden lg:flex',
                        'child' => [
                            [
                                'nodeName' => 'rich-text',
                                'nodes' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>'
                            ],
                            '返回'
                        ]
                    ] : [],
                    [
                        'nodeName' => 'div',
                        'class' => 'text-base lg:block hidden',
                        'child' => $this->title ?: '列表数据'
                    ]
                ]
            ];
        }

        return [
            [
                'nodeName' => 'div',
                'class' => 'flex-grow lg:w-10 flex justify-center lg:justify-start',
                'child' => $header
            ],
            [
                'nodeName' => 'div',
                'class' => 'flex-none flex lg:flex-row flex-col gap-4 bg-white p-4 rounded shadow-sm lg:shadow-none lg:p-0 mt-1 lg:mt-0',
                'child' => [
                    $this->quickFilter ? [
                        'nodeName' => 'div',
                        'class' => 'flex gap-2 lg:flex-row flex-col',
                        'child' => $this->quickFilter
                    ] : [],
                    $this->action ? [
                        'nodeName' => 'div',
                        'class' => 'flex gap-2 lg:flex-row flex-col',
                        'child' => $this->action
                    ] : []
                ]
            ]
        ];
    }

    public function sortableNode()
    {
        $this->params['selectable'] = true;
        $this->params['draggable'] = true;
        $this->params['default-expand-all'] = true;
        
        $this->class .= ' mt-4';
        return [
            'nodeName' => 'div',
            'child' => [
                'nodeName' => 'app-tree',
                'class' => $this->class,
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
        $this->params['vBind:row-key'] = "row => row['$this->key']";
        $this->params['bottom-bordered'] = false;
        return [
            'nodeName' => 'app-table',
            'class' => $this->class,
            'url' => $this->url,
            'urlBind' => true,
            'n-params' => $this->params,
            'columns' => $this->columns,
            'vBind:filter' => 'data.filter',
            'page-prefix' => $this->bath,
            'table-layout' => 'fixed',
            'child' => [
                [
                    'vSlot:empty' => '',
                    'nodeName' => 'app-empty',
                    'title' => '暂无数据',
                    'content' => '暂未找到数据，您可以尝试重新加载'

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
        JS;
        $this->scriptReturn[] = 'dataRef';

        $showNode = $this->showNode();
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
                            'nodeName' => 'div',
                            'class' => 'border-r border-gray-200 flex-none bg-white overflow-auto app-scrollbar',
                            'child' => $this->side['left']
                        ] : [],
                        [
                            'nodeName' => 'div',
                            'class' => 'flex-grow w-10 flex flex-col',
                            'child' => [
                                [
                                    'nodeName' => 'div',
                                    'class' => 'flex-none flex lg:flex-row flex-col gap-2 lg:items-center p-4 pb-0 lg:pb-4 lg:border-b lg:border-gray-300 lg:bg-white lg:shadow-sm z-10',
                                    'child' => $this->headNode()
                                ],
                                [
                                    'nodeName' => 'div',
                                    'class' => 'flex-grow bg-gray-100 overflow-auto app-scrollbar',
                                    'child' => [
                                        'nodeName' => 'div',
                                        'class' => 'p-5',
                                        'child' => [
                                            $this->header,
                                            [
                                                'nodeName' => 'div',
                                                'class' => 'hidden lg:flex justify-end gap-2',
                                                'child' => $showNode
                                            ],
                                            [
                                                'nodeName' => 'div',
                                                'class' => 'flex flex-col lg:flex-row lg:items-start gap-4',
                                                'child' => [
                                                    $this->page['left'] ?: [],
                                                    [
                                                        'nodeName' => 'div',
                                                        'class' => 'flex-grow lg:w-10',
                                                        'child' => $this->sortable ? $this->sortableNode() : $this->tableNode(),
                                                    ],
                                                    $this->page['right'] ?: [],
                                                ]
                                            ],
                                            $this->footer,
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        $this->side['right'] ? [
                            'nodeName' => 'div',
                            'class' => 'overflow-auto app-scrollbar border-l border-gray-200 flex-none bg-white',
                            'child' => $this->side['right']
                        ] : [],
                    ]
                ],
            ],
            'setupScript' => implode("\n", $this->script) . "\n" . ' return {' . implode(",", $this->scriptReturn) . '}'
        ];
    }
}

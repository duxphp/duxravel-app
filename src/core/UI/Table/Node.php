<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Form\Cascader;
use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Icon;

/**
 * 表格节点
 * @package Duxravel\Core\UI\Table
 */
class Node
{
    private string $url;
    private ?string $key;
    private ?string $title;
    private bool $tree = false;
    private bool $urlBind = true;
    private string $class = '';
    private array $params = [];
    private array $data = [];
    private array $columns = [];
    private array $expand = [];
    private array $filter = [];
    private array $quickFilter = [];
    private array $action = [];
    private array $bath = [];
    private array $page = [];
    private array $type = [];
    private array $side = [];
    private array $sideSize = [];
    private array $header = [];
    private array $footer = [];
    private array $script = [];
    private array $scriptReturn = [];
    private array $scriptData = [];
    private ?string $eventName = null;
    private array $bindFilter = [];
    private array $columnsData = [];
    private array $statics = [];
    private ?int $limit = null;
    private bool $debug = false;

    /**
     * Node constructor.
     * @param string      $url
     * @param string      $key
     * @param string|null $title
     */
    public function __construct(string $url, string $key, ?string $title = '')
    {
        $this->url = $url;
        $this->key = $key;
        $this->title = $title;
    }

    /**
     * @param $urlBind
     * @return $this
     */
    public function urlBind($urlBind): self
    {
        $this->urlBind = $urlBind;
        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function class($class): self
    {
        $this->class .= ' ' . $class;
        return $this;
    }

    /**
     * @param $params
     * @return $this
     */
    public function params($params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function tree($status): self
    {
        $this->tree = $status;
        return $this;
    }

    /**
     * @param array $filter
     * @param string $type
     * @return $this
     */
    public function data(array $filter,string $type = 'filter'): self
    {
        $this->data[$type] = $filter;
        return $this;
    }

    /**
     * @param $content
     * @param $return
     * @return $this
     */
    public function script($content, $return): self
    {
        if ($content instanceof \Closure) {
            $this->script[] = $content();
        } else {
            $this->script[] = $content;
        }
        $this->scriptReturn[] = $return;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function scriptData($data): self
    {
        $this->scriptData = array_merge($this->scriptData, $data);
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function columns(array $node): self
    {
        $this->columns = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function expand(array $node): self
    {
        $this->expand = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function type(array $node): self
    {
        $this->type = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function filter(array $node): self
    {
        $this->filter = array_values(array_filter($node));
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function quickFilter(array $node): self
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
    public function action(array $node): self
    {
        $this->action = $node;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function bath(array $node): self
    {
        $this->bath = $node;
        return $this;
    }

    /**
     * @param string|null $eventName
     * @return $this
     */
    public function eventName(?string $eventName): self
    {
        $this->eventName = $eventName;
        return $this;
    }

    /**
     * @param        $node
     * @param string $type
     * @param false  $resize
     * @param string $width
     * @return $this
     */
    public function side($node, string $type = 'left', bool $resize = false, string $width = '100px'): self
    {
        $this->side[$type] = is_callable($node) ? $node() : $node;
        if ($resize) {
            $this->sideSize[$type] = $width;
        }
        return $this;
    }

    /**
     * @param        $node
     * @param string $type
     * @return $this
     */
    public function page($node, string $type = 'left'): self
    {
        $this->page[$type] = is_callable($node) ? $node() : $node;
        return $this;
    }

    /**
     * 绑定其他筛选数据
     * @param string $bindFilter
     * @return $this
     */
    public function bindFilter(array $bindFilter): self
    {
        $this->bindFilter = $bindFilter;
        return $this;
    }

    /**
     * 绑定数据
     * @param array $columnsData
     * @return $this
     */
    public function columnsData(array $columnsData): self
    {
        $this->columnsData = $columnsData;
        return $this;
    }

    /**
     * 分页数量
     * @param int $num
     * @return $this
     */
    public function limit(int $num): self
    {
        $this->limit = $num;
        return $this;
    }

    /**
     * @param $node
     * @return $this
     */
    public function header($node): self
    {
        $this->header = $node;
        return $this;
    }

    /**
     * @param $node
     * @return $this
     */
    public function footer($node): self
    {
        $this->footer = $node;
        return $this;
    }

    /**
     * 调试
     * @param bool $debug
     * @return $this
     */
    public function debug(bool $debug = true): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return array[]
     */
    private function headNode(): array
    {

        if ($this->filter) {
            $this->quickFilter[] = [
                'nodeName' => 'a-trigger',
                'position' => 'br',
                'trigger' => 'click',
                'child' => [
                    [
                        'nodeName' => 'a-button',
                        'type' => 'secondary',
                        'child' => [
                            '筛选',
                            [
                                'vSlot:icon' => '',
                                'nodeName' => 'icon-filter',
                            ]
                        ]
                    ],
                    [
                        'vSlot:content' => '',
                        'nodeName' => 'div',
                        'class' => 'flex flex-col rounded shadow bg-white dark:bg-blackgray-1 dark:text-gray-400 p-2 w-56',
                        'child' => $this->filter
                    ]
                ]
            ];
        }

        if ($this->type) {
            $header = [
                'nodeName' => 'a-radio-group',
                'name' => 'type',
                'type' => 'button',
                'vModel:modelValue' => 'data.filter.type',
                'child' => $this->type
            ];
        }

        $header2 = array_filter(array_merge($this->quickFilter, $this->action));
        if(empty($header) && empty($header2)){
            return [];
        }

        return [
            [
                'nodeName' => 'div',
                'class' => 'flex-grow lg:w-10 flex justify-center lg:justify-start',
                'child' => $header
            ],
            [
                'nodeName' => 'div',
                'class' => 'flex-none flex gap-2',
                'child' => $header2
            ]
        ];
    }

    /**
     * @return array
     */
    public function tableNode(): array
    {
        // 指定行key
        $this->params['row-key'] = $this->key;

        // 设置扩展行
        if ($this->expand) {
            $this->params['vChild:expandable'] = $this->expand;
        }

        // 分组表头线
        $children = false;
        foreach ($this->columns as $col) {
            if ($col['children']) {
                $children = true;
                break;
            }
        }
        if ($children) {
            $this->params['bordered'] = [
                'headerCell' => true
            ];
        }

        return [
            'nodeName' => 'app-table',
            'requestEventName' => $this->eventName ?: md5(url_class($this->url)['class']),
            'class' => $this->class,
            'url' => $this->url,
            'urlBind' => $this->urlBind,
            'n-params' => array_merge(app('config')->get('table.default.nParams',[]),$this->params),
            'columns' => $this->columns,
            'vBind:columnsData' => 'dux.util.watchAssignObject('. implode(',',$this->columnsData) .')',
            'vBind:filter' => empty($this->bindFilter) ? 'data.filter' : 'dux.util.watchAssignObject('. implode(',',$this->bindFilter) .',data.filter)',
            'select' => (bool)$this->bath,
            'table-layout-fixed' => true,
            'child' => [
                'vSlot:footer' => 'footer',
                'nodeName' => 'div',
                'class' => 'flex gap-2',
                'child' => $this->bath
            ],
            'nowrap' => app('config')->get('table.default.nowrap',false),
            'vBind:defaultData' => isset($this->data['data']) ? 'data.data' : false,
            'limit' => $this->limit,
            'defaultExpand' => $this->tree,
        ];
    }

    /**
     * 前端静态覆盖数据
     * @param array $statics
     * @return $this
     */
    public function statics(array $statics): self
    {
        $this->statics = $statics;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {

        /*$this->script[] = <<<JS
        JS;
        $this->scriptReturn[] = 'dataRef';*/

        $value = [
            'filter' => $this->data['filter'] ?: [],
            'show'   => $this->data['show'] ?: [],
            'data'   => $this->data['data'] ?? null
        ];
        $value = array_merge($this->scriptData, $value);

        $headNode = $this->headNode();

        $render = [
            'node' => [
                'nodeName' => 'app-form',
                'value' => $value,
                'child' => [
                    'nodeName' => 'div',
                    'class' => 'flex h-screen',
                    'vSlot' => '{value: data}',
                    'child' => [
                        $this->side['left'] ? [
                            'nodeName' => isset($this->sideSize['left']) ? 'a-resize-box' : 'div',
                            'style' => $this->sideSize['left'] ? 'width:' . $this->sideSize['left'] : '',
                            'directions' => ['right'],
                            'class' => 'border-r border-gray-200 dark:border-gray-700 flex-none bg-white dark:bg-blackgray-4 h-screen',
                            'child' => $this->side['left']
                        ] : [],

                        [
                            'nodeName' => 'app-layout',
                            'class' => 'flex-grow w-10',
                            'title' => $this->title ?: '列表数据',
                            'child' => [
                                [
                                    'vSlot' => '',
                                    'nodeName' => 'div',
                                    'class' => 'flex flex-row items-start gap-4 p-4',
                                    'child' => [
                                        $this->page['left'] ?: [],
                                        [
                                            'nodeName' => 'div',
                                            'class' => 'flex-grow lg:w-10 p-4 bg-white dark:bg-blackgray-4 rounded shadow',
                                            'child' => [
                                                $this->header ? [
                                                    'nodeName' => 'div',
                                                    'class' => 'pb-4',
                                                    'child' => $this->header
                                                ] : [],
                                                $headNode ? [
                                                    'nodeName' => 'div',
                                                    'class' => 'flex-none flex flex-row gap-2 items-center pb-4',
                                                    'child' => $headNode
                                                ] : [],
                                                $this->tableNode(),
                                                $this->footer ? [
                                                    'nodeName' => 'div',
                                                    'class' => 'pb-4',
                                                    'child' => $this->footer
                                                ] : [],
                                            ],
                                        ],
                                        $this->page['right'] ?: [],
                                    ]
                                ]
                            ]
                        ],


                        $this->side['right'] ? [
                            'nodeName' => isset($this->sideSize['right']) ? 'a-resize-box' : 'div',
                            'style' => $this->sideSize['right'] ? 'width:' . $this->sideSize['left'] : '',
                            'directions' => ['left'],
                            'class' => 'border-l border-gray-200 dark:border-gray-700 flex-none bg-white dark:bg-blackgray-4 h-screen',
                            'child' => $this->side['right']
                        ] : [],
                    ]
                ],
            ],
            'setupScript' => $this->renderScript(),
            'static' => $this->renderStatics()
        ];

        if($this->debug){
            $render['debug'] = $this->debug;
        }

        return $render;
    }

    /**
     * 渲染table核心
     * @return array[]
     */
    public function renderTableCore(): array
    {
        $value = [
            'filter' => $this->data['filter'] ?: [],
            'show'   => $this->data['show'] ?: [],
            'data'   => $this->data['data'] ?? null
        ];
        $value = array_merge($this->scriptData, $value);

        $headNode = $this->headNode();

        return [
            'nodeName' => 'app-form',
            'value' => $value,
            'child' => [
                'nodeName' => 'div',
                'class' => 'flex flex-row items-start gap-4 p-4',
                'vSlot' => '{value: data}',
                'child' => [
                    $this->page['left'] ?: [],
                    [
                        'nodeName' => 'div',
                        'class' => 'flex-grow lg:w-10 p-4 bg-white dark:bg-blackgray-4 rounded shadow',
                        'child' => [
                            $this->header ? [
                                'nodeName' => 'div',
                                'class' => 'pb-4',
                                'child' => $this->header
                            ] : [],
                            $headNode ? [
                                'nodeName' => 'div',
                                'class' => 'flex-none flex flex-row gap-2 items-center pb-4',
                                'child' => $headNode
                            ] : [],
                            $this->tableNode(),
                            $this->footer ? [
                                'nodeName' => 'div',
                                'class' => 'pb-4',
                                'child' => $this->footer
                            ] : [],
                        ],
                    ],
                    $this->page['right'] ?: [],
                ]
            ]
        ];
    }

    /**
     * 渲染表单js
     * @return string
     */
    public function renderScript()
    {
        return implode("\n", $this->script) . "\n" . ' return {' . implode(",", $this->scriptReturn) . '}';
    }

    /**
     * 前端覆盖数据
     * @return array
     */
    public function renderStatics(): array
    {
        $statics = [];
        $stringData = ['style','scriptString'];
        foreach ($this->statics as $key => $vo){
            if(in_array($key,$stringData) && is_array($vo)){
                $vo = implode("\n",$vo);
            }
            $statics[$key] = $vo;
        }
        return $statics;
    }

}

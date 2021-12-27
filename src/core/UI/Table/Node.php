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
    private bool $tree = false;
    private string $class = '';
    private array $params = [];
    private array $data = [];
    private array $columns = [];
    private array $expand = [];
    private array $filter = [];
    private array $quickFilter = [];
    private array $action = [];
    private array $bath = [];
    private array $type = [];
    private array $side = [];
    private array $sideSize = [];
    private array $header = [];
    private array $footer = [];
    private array $script = [];
    private array $scriptReturn = [];
    private array $scriptData = [];

    /**
     * Node constructor.
     *
     * @param string      $url
     * @param string      $method
     * @param string|null $title
     */
    public function __construct(string $url, string $key, ?string $title = '')
    {
        $this->url = $url;
        $this->key = $key;
        $this->title = $title;
    }

    /**
     * @param $class
     *
     * @return $this
     */
    public function class($class)
    {
        $this->class .= ' ' . $class;
        return $this;
    }

    /**
     * @param $params
     *
     * @return $this
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array $filter
     *
     * @return $this
     */
    public function data(array $filter)
    {
        $this->data['filter'] = $filter;
        return $this;
    }

    /**
     * @param $content
     *
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

    /**
     * @param $data
     */
    public function scriptData($data)
    {
        $this->scriptData = array_merge($this->scriptData, $data);
        return $this;
    }

    /**
     * @param $config
     *
     * @return $this
     */
    public function tree($config)
    {
        $this->tree = $config;
        return $this;
    }

    /**
     * @param array $node
     *
     * @return $this
     */
    public function columns(array $node)
    {
        $this->columns = $node;
        return $this;
    }

    /**
     * @param array $node
     *
     * @return $this
     */
    public function expand(array $node)
    {
        $this->expand = $node;
        return $this;
    }

    /**
     * @param array $node
     *
     * @return $this
     */
    public function type(array $node)
    {
        $this->type = $node;
        return $this;
    }

    /**
     * @param array $node
     *
     * @return $this
     */
    public function filter(array $node)
    {
        $this->filter = array_values(array_filter($node));
        return $this;
    }

    /**
     * @param array $node
     *
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
     *
     * @return $this
     */
    public function action(array $node)
    {
        $this->action = $node;
        return $this;
    }

    /**
     * @param array $node
     *
     * @return $this
     */
    public function bath(array $node)
    {
        $this->bath = $node;
        return $this;
    }

    /**
     * @param        $node
     * @param string $type
     * @param false  $resize
     * @param string $width
     *
     * @return $this
     */
    public function side($node, $type = 'left', bool $resize = false, string $width = '100px')
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
     *
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
     * @return array[]
     */
    private function headNode()
    {

        if ($this->filter) {
            $this->quickFilter[] = [
                'nodeName' => 'a-trigger',
                'position' => 'br',
                'trigger' => 'click',
                //'popupVisible' => 'show',
                //'width' => 300,
                //'class' => 'filter-popover',
                /*'vBind:ref' => <<<JS
                            el => dataRef.value = el
                            JS,*/
                'child' => [
                    [
                        'nodeName' => 'a-button',
                        'type' => 'secondary',
                        //'attr-type' => 'button',
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

        return [
            [
                'nodeName' => 'div',
                'class' => 'flex-grow lg:w-10 flex justify-center lg:justify-start',
                'child' => $header
            ],
            [
                'nodeName' => 'div',
                'class' => 'flex-none flex gap-2',
                'child' => array_filter(array_merge($this->quickFilter, $this->action))
            ]
        ];
    }

    /**
     * @return array
     */
    public function tableNode()
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
            'requestEventName' => url_class($this->url)['class'],
            'class' => $this->class,
            'url' => $this->url,
            'urlBind' => true,
            'n-params' => $this->params,
            'columns' => $this->columns,
            'vBind:filter' => 'data.filter',
            'select' => $this->bath ? true : false,
            'table-layout-fixed' => true,
            'child' => [
                'vSlot:footer' => 'footer',
                'nodeName' => 'div',
                'class' => 'flex gap-2',
                'child' => $this->bath
            ]
        ];
    }


    /**
     * @return array
     */
    public function render()
    {

        clock($this->sideSize);
        /*$this->script[] = <<<JS
        JS;
        $this->scriptReturn[] = 'dataRef';*/

        $value = [
            'filter' => $this->data['filter'] ?: [],
            'show' => $this->data['show'] ?: []
        ];
        $value = array_merge($this->scriptData, $value);


        return [
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
                                                [
                                                    'nodeName' => 'div',
                                                    'class' => 'flex-none flex flex-row gap-2 items-center pb-4',
                                                    'child' => $this->headNode()

                                                ],
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
            'setupScript' => implode("\n", $this->script) . "\n" . ' return {' . implode(",", $this->scriptReturn) . '}'
        ];
    }
}

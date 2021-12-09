<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Icon;

/**
 * 表单节点
 * @package Duxravel\Core\UI\Form
 */
class Node
{

    private string $url = '';
    private string $method = 'get';
    private ?string $title = '';
    private bool $back = false;
    private bool $dialog = false;
    private bool $vertical = false;
    private array $element = [];
    private array $data = [];
    private array $side = [];
    private array $script = [];

    /**
     * Node constructor.
     * @param string $action
     * @param string $method
     * @param string|null $title
     */
    public function __construct(string $action, string $method, ?string $title = '')
    {
        $this->url = $action;
        $this->method = $method;
        $this->title = $title;
    }

    /**
     * @param bool $bool
     */
    public function back(bool $bool)
    {
        $this->back = $bool;
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

    /**
     * @param bool $bool
     * @return $this
     */
    public function dialog(bool $bool)
    {
        $this->dialog = $bool;
        $this->vertical = true;
        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function vertical(bool $bool)
    {
        $this->vertical = $bool;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function element(array $node)
    {
        $this->element = $node;
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
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    private function headNode()
    {
        if ($this->dialog) {
            return [
                'nodeName' => 'div',
                'class' => 'arco-modal-header',
                'child' => [
                    [
                        'nodeName' => 'div',
                        'class' => 'arco-modal-title',
                        'child' => $this->title ?: '信息详情'
                    ],
                    [
                        'nodeName' => 'route',
                        'type' => 'back',
                        'class' => 'arco-modal-close-btn',
                        'child' => [
                            'nodeName' => 'span',
                            'class' => 'arco-icon-hover arco-icon-hover-size-medium',
                            'child' => (new Icon('close'))->getRender()
                        ]
                    ]
                ]
            ];
        }

        return [
            'nodeName' => 'div',
            'class' => ' flex items-center p-4 border-b border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm z-10',
            'child' => [
                [
                    'nodeName' => 'div',
                    'class' => 'flex-none',
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
                            'class' => 'text-base',
                            'child' => $this->title ?: '信息详情'
                        ]
                    ]
                ],
                [
                    'nodeName' => 'div',
                    'class' => 'flex-grow items-center hidden lg:flex justify-end gap-2',
                    'child' => [
                        [
                            'nodeName' => 'n-button',
                            'type' => 'default',
                            'attr-type' => 'reset',
                            'child' => '重置',
                        ],
                        [
                            'nodeName' => 'n-button',
                            'attr-type' => 'submit',
                            'type' => 'primary',
                            'child' => '提交',
                        ],
                    ]
                ],
            ]
        ];
    }

    private function renderPage()
    {
        return [
            $this->side['left'] ? [
                'nodeName' => 'div',
                'class' => 'flex-none flex h-screen  flex-col',
                'child' => $this->side['left']
            ] : [],
            [
                'nodeName' => 'app-layout',
                'class' => 'flex-grow w-10',
                'title' => $this->title ?: '信息详情',
                'form' => true,
                'child' => [
                    [
                        'nodeName' => 'div',
                        'class' => 'p-5',
                        'child' => [
                            [
                                'nodeName' => 'div',
                                'child' => $this->element
                            ],
                            [
                                'nodeName' => 'div',
                                'class' => 'flex items-center justify-end gap-2 flex-row ',
                                'child' => [

                                    [
                                        'nodeName' => 'route',
                                        'type' => 'back',
                                        'child' => [
                                            'type' => "outline",
                                            'nodeName' => 'a-button',
                                            'child' => '返回',
                                        ]
                                    ],
                                    [
                                        'nodeName' => 'a-button',
                                        'html-type' => 'submit',
                                        'type' => 'primary',
                                        'child' => '提交',
                                    ],
                                ]
                            ],

                        ],
                    ],
                ],
            ],
            $this->side['right'] ? [
                'nodeName' => 'div',
                'class' => 'flex-none flex h-screen  flex-col',
                'child' => $this->side['right']
            ] : [],
        ];
    }

    private function renderDialog()
    {

        return [
            'nodeName' => 'app-dialog',
            'title' => $this->title ?: '信息详情',
            'class' => 'flex-grow',
            'child' => [
                [
                    'nodeName' => 'div',
                    'vSlot:default' => '',
                    'class' => 'flex',
                    'child' => [
                        $this->side['left'] ? [
                            'nodeName' => 'div',
                            'class' => 'flex-none',
                            'child' => $this->side['left']
                        ] : [],
                        [
                            'nodeName' => 'div',
                            'class' => 'flex-grow p-5 pb-0',
                            'child' => $this->element
                        ],
                        $this->side['right'] ? [
                            'nodeName' => 'div',
                            'class' => 'flex-none',
                            'child' => $this->side['right']
                        ] : []
                    ]
                ],
                [
                    'nodeName' => 'div',
                    'vSlot:footer' => '',
                    'class' => 'arco-modal-footer',
                    'child' => [
                        [
                            'nodeName' => 'route',
                            'type' => 'back',
                            'child' => [
                                'nodeName' => 'a-button',
                                'child' => '取消'
                            ]
                        ],
                        [
                            'nodeName' => 'a-button',
                            'type' => 'primary',
                            'html-type' => 'submit',
                            'vBind:loading'=>"loading",
                            'child' => '提交'
                        ],
                    ]
                ]
            ]

        ];

    }

    public function render()
    {
        return [
            'node' => [
                'nodeName' => 'app-form',
                'url' => $this->url,
                'method' => $this->method,
                'value' => $this->data,
                'layout' => $this->vertical ? 'vertical' : 'horizontal',
                'child' => [
                    'nodeName' => 'div',
                    'class' => 'flex',
                    'vSlot' => '{value: data, submitStatus: loading}',
                    'child' => $this->dialog ? $this->renderDialog() : $this->renderPage()
                ]
            ],
            'setupScript' => implode("\n", $this->script)

        ];
    }

}

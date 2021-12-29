<?php

namespace Duxravel\Core\UI\Form;

/**
 * 表单节点
 * @package Duxravel\Core\UI\Form
 */
class Node
{

    private string $url;
    private string $method;
    private ?string $title;
    private bool $back = false;
    private bool $dialog = false;
    private bool $vertical = false;
    private array $element = [];
    private array $data = [];
    private array $side = [];
    private array $script = [];
    private array $scriptReturn = [];

    /**
     * Node constructor.
     * @param string      $action
     * @param string      $method
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
    public function back(bool $bool): void
    {
        $this->back = $bool;
    }

    /**
     * @param Closure|string $content
     * @param string         $return
     * @return $this
     */
    public function script($content, string $return): self
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
     * @param bool $bool
     * @return $this
     */
    public function dialog(bool $bool): self
    {
        $this->dialog = $bool;
        $this->vertical = true;
        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function vertical(bool $bool): self
    {
        $this->vertical = $bool;
        return $this;
    }

    /**
     * @param array $node
     * @return $this
     */
    public function element(array $node): self
    {
        $this->element = $node;
        return $this;
    }

    /**
     * @param Closure|array $node
     * @param string        $type
     * @return $this
     */
    public function side($node, string $type = 'left'): self
    {
        $this->side[$type] = is_callable($node) ? $node() : $node;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function data($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 渲染页面
     * @return array[]
     */
    private function renderPage(): array
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
                'back' => $this->back,
                'vBind:formLoading' => 'loading',
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
                                    $this->back ? [
                                        'nodeName' => 'route',
                                        'type' => 'back',
                                        'child' => [
                                            'type' => "outline",
                                            'nodeName' => 'a-button',
                                            'child' => '返回',
                                        ]
                                    ] : [],
                                    [
                                        'nodeName' => 'a-button',
                                        'html-type' => 'submit',
                                        'vBind:loading' => "loading",
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

    /**
     * 渲染弹窗
     * @return array
     */
    private function renderDialog(): array
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
                            'vBind:loading' => "loading",
                            'child' => '提交'
                        ],
                    ]
                ]
            ]

        ];

    }

    /**
     * 渲染布局
     * @return array
     */
    public function render(): array
    {
        return [
            'node' => [
                'nodeName' => 'app-form',
                'url' => $this->url,
                'method' => $this->method,
                'value' => $this->data,
                'layout' => $this->vertical ? 'vertical' : 'horizontal',
                'back' => $this->back,
                'child' => [
                    'nodeName' => 'div',
                    'class' => 'flex',
                    'vSlot' => '{value: data, submitStatus: loading}',
                    'child' => $this->dialog ? $this->renderDialog() : $this->renderPage()
                ]
            ],
            'setupScript' => implode("\n", $this->script) . "\n" . ' return {' . implode(",", $this->scriptReturn) . '}'

        ];
    }

}

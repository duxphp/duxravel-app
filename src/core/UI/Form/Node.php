<?php

namespace Duxravel\Core\UI\Form;

/**
 * ่กจๅ่็น
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
    private string $layout = 'app-layout';
    private ?array $bottom = null;
    private array $statics = [];

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
     * ๅธๅฑๅฑๆง
     * @param $layout
     * @return $this
     */
    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * ๅบ้จ็ปไปถ
     * @param null|array $bottom
     * @return $this
     */
    public function bottom(?array $bottom): self
    {
        $this->bottom = $bottom;
        return $this;
    }

    /**
     * ๆธฒๆ้กต้ข
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
                'nodeName' => $this->layout,
                'class' => 'flex-grow w-10',
                'title' => $this->title ?: 'ไฟกๆฏ่ฏฆๆ',
                'form' => true,
                'back' => $this->back,
                'vBind:formLoading' => 'loading',
                'child' => [
                    [
                        'nodeName' => 'div',
                        'class' => 'p-4',
                        'child' => [
                            [
                                'nodeName' => 'div',
                                'child' => $this->element
                            ],
                            [
                                'nodeName' => 'div',
                                'class' => 'flex items-center justify-end gap-2 flex-row ',
                                'child' => !is_null($this->bottom) ? $this->bottom : [
                                    $this->back ? [
                                        'nodeName' => 'route',
                                        'type' => 'back',
                                        'child' => [
                                            'type' => "outline",
                                            'nodeName' => 'a-button',
                                            'child' => '่ฟๅ',
                                        ]
                                    ] : [],
                                    [
                                        'nodeName' => 'a-button',
                                        'html-type' => 'submit',
                                        'vBind:loading' => "loading",
                                        'type' => 'primary',
                                        'child' => $this->back ? 'ๆไบค' : 'ไฟๅญ',
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
     * ๆธฒๆๅผน็ช
     * @return array
     */
    private function renderDialog(): array
    {

        return [
            'nodeName' => 'app-dialog',
            'title' => $this->title ?: 'ไฟกๆฏ่ฏฆๆ',
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
                    'child' => !is_null($this->bottom) ? $this->bottom : [
                        [
                            'nodeName' => 'route',
                            'type' => 'back',
                            'child' => [
                                'nodeName' => 'a-button',
                                'child' => 'ๅๆถ'
                            ]
                        ],
                        [
                            'nodeName' => 'a-button',
                            'type' => 'primary',
                            'html-type' => 'submit',
                            'vBind:loading' => "loading",
                            'child' => 'ๆไบค'
                        ],
                    ]
                ]
            ]

        ];

    }

    /**
     * ๅ็ซฏ้ๆ่ฆ็ๆฐๆฎ
     * @param array $statics
     * @return $this
     */
    public function statics(array $statics): self
    {
        $this->statics = $statics;
        return $this;
    }

    /**
     * ๆธฒๆๅธๅฑ
     * @return array
     */
    public function render(): array
    {
        return [
            'node' => $this->renderFormCore(),
            'setupScript' => $this->renderScript(),
            'static' => $this->renderStatics()
        ];
    }

    /**
     * ๆธฒๆ่กจๅๆ?ธๅฟไปฃ็?
     * @return array
     */
    public function renderFormCore(){
        return [
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
        ];
    }

    /**
     * ๆธฒๆ่กจๅjs
     * @return string
     */
    public function renderScript(){
        return implode("\n", $this->script) . "\n" . ' return {' . implode(",", $this->scriptReturn) . '}';
    }

    /**
     * ๅ็ซฏ่ฆ็ๆฐๆฎ
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

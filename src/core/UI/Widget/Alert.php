<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class Alert
 * @package Duxravel\Core\UI\Widget
 */
class Alert extends Widget
{

    protected string $type = 'info';
    private ?string $title;
    private $content;

    /**
     * Alert constructor.
     * @param string|array $content
     * @param string|null $title
     * @param callable|null $callback
     */
    public function __construct($content, string $title = null, callable $callback = NULL)
    {
        $this->title = $title;
        $this->content = $content;
        $this->callback = $callback;
    }

    /**
     * 文本类型
     * @param $name
     * @return $this
     */
    public function type($name): self
    {
        $this->type = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->class('p-5 rounded border shadow-sm');

        switch ($this->type) {
            case 'success':
                $icon = (new Icon('check-circle'))->render();
                $this->class('text-green-900 bg-green-200 border-green-400');
                break;
            case 'error':
                $icon = (new Icon('x-circle'))->render();
                $this->class('text-red-900 bg-red-200 border-red-400');
                break;
            case 'warning':
                $icon = (new Icon('exclamation-circle'))->render();
                $this->class('text-yellow-900 bg-yellow-200 border-yellow-400');
                break;
            case 'info':
            default:
            $icon = (new Icon('information-circle'))->render();
                $this->class('text-blue-900 bg-blue-200 border-blue-400');
                break;
        }

        if ($icon) {
            $icon = "<div class='flex-none w-6 h-6 hidden lg:block'>$icon</div>";
        }
        $title = '';
        if ($this->title) {
            $title = "<div class='font-medium'>$this->title</div>";
        }
        if (is_array($this->content)) {
            $inner = [];
            foreach ($this->content as $vo) {
                $inner[] = "<li>$vo</li>";
            }
            $inner = implode('', $inner);
            $body = <<<HTML
                <ul class="list-disc mt-2 lg:ml-10 opacity-90">
                $inner
                </ul>
            HTML;
        } else {
            $body = "<div class='mt-1  lg:ml-10 opacity-90'>$this->content</div>";
        }

        return <<<HTML
            <div {$this->toElement()}>
                <div class="flex-grow flex items-center gap-4">
                    $icon
                    $title
                </div>
                $body
            </div>
        HTML;
    }
}

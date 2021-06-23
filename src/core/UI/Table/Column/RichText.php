<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class RichText
 */
class RichText implements Component
{
    private array $desc = [];
    private array $image = [];

    /**
     * @param string $label
     * @param callable|null $callback
     * @return $this
     */
    public function desc(string $label, ?callable $callback = null): self
    {
        $this->desc[] = ['label' => $label, 'callback' => $callback];
        return $this;
    }

    /**
     * @param string $label
     * @param int $width
     * @param int $height
     * @param string $placeholder
     * @param callable|null $callback
     * @return $this
     */
    public function image(string $label, int $width = 10, int $height = 10, string $placeholder = '', ?callable $callback = null): self
    {
        $this->image = [
            'label' => $label,
            'width' => $width,
            'height' => $height,
            'placeholder' => $placeholder,
            'callback' => $callback
        ];
        return $this;
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {

        // 设置图片
        $imageHtml = '';
        if ($this->image) {
            $url = Tools::parsingArrData($data, $this->image['label'], true);
            if ($this->image['callback'] instanceof \Closure) {
                $url = call_user_func($this->image['callback'], $url, $data);
            }
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                $url = route('service.image.placeholder', ['w' => 100, 'h' => 100, 't' => $this->image['placeholder'] ?: '暂无']);
            }
            $imageHtml = <<<HTML
                <span class="flex-none avatar w-{$this->image['width']} h-{$this->image['height']}" style="background-image: url('$url');"></span>
            HTML;
        }

        // 设置描述
        $descHtml = '';
        if ($this->desc) {
            $desc = [];
            foreach ($this->desc as $vo) {
                $var = Tools::parsingArrData($data, $vo['label']);
                if ($vo['callback'] instanceof \Closure) {
                    $var = call_user_func($vo['callback'], $var, $data);
                }
                $desc[] = is_array($var) ? implode(' ', $var) : $var;
            }
            $descHtml = implode(' ', array_map(static function ($value) {
                if ($value === null) {
                    return '';
                }
                $value = $value ?: '-';
                return "<div class='text-gray-500'>$value</div>";
            }, $desc));
        }

        return <<<HTML
            <div class="flex items-center space-x-2">
                $imageHtml
                <div class="flex-grow">
                <div>$value</div>
                $descHtml
                </div>
            </div>
        HTML;

    }

}

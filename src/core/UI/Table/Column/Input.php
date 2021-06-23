<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Input
 */
class Input implements Component
{

    private array $params;
    private string $field;
    private $url;

    /**
     * Toggle constructor.
     * @param string $field
     * @param string|array $url
     * @param array $params
     */
    public function __construct(string $field = '', $url = '', array $params = [])
    {
        $this->field = $field;
        $this->url = $url;
        $this->params = $params;
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {
        $params = [];
        foreach ($this->params as $key => $vo) {
            $params[$key] = Tools::parsingArrData($data, $vo, true);
        }
        return (new \Duxravel\Core\UI\Form\Text('文本', 'status'))
            ->attr('data-js', 'form-input')
            ->attr('data-params', json_encode($params))
            ->attr('data-field', $this->field)
            ->attr('data-url', is_array($this->url) ? route(...$this->url) : route($this->url))
            ->render($value);
    }

}

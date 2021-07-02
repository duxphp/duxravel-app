<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class Toggle
 */
class Toggle implements Component
{

    private array $params;
    private string $field;
    private string $url;

    /**
     * Toggle constructor.
     * @param string $field
     * @param string $url
     * @param array $params
     */
    public function __construct(string $field = '', string $url = '', array $params = [])
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
        return (new \Duxravel\Core\UI\Form\Toggle('状态', 'status'))
            ->attr('data-js', 'form-switch')
            ->attr('data-params', json_encode($params))
            ->attr('data-field', $this->field)
            ->attr('data-url', route($this->url, $params))
            ->render($value);
    }

}

<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Append\Element;

/**
 * 链接组件
 * Class Link
 * @package Duxravel\Core\UI\Widget
 */
class Link extends Widget
{
    use Element;

    protected string $name;
    protected string $route;
    protected array $params = [];
    protected ?\Closure $show = null;
    protected string $button = '';
    protected string $type = 'default';
    protected array $data = [];
    protected array $class = [];
    protected array $typeConfig = [];
    protected string $icon = '';
    protected string $auth = '';

    /**
     * Link constructor.
     * @param string $name
     * @param string $route
     * @param array $params
     * @param callable|null $callback
     */
    public function __construct(string $name, string $route = '', array $params = [], callable $callback = NULL)
    {
        $this->name = $name;
        $this->route = $route;
        $this->params = $params ?: [];
        $this->callback = $callback;
    }

    /**
     * 链接类型
     * @param string $name
     * @param array $config
     * @return $this
     */
    public function type(string $name = 'default', array $config = []): self
    {
        $this->type = $name;
        $this->typeConfig = $config;
        return $this;
    }

    public function icon($icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * 设置为按钮
     * @param string $type
     * @return $this
     */
    public function button(string $type = 'primary'): self
    {
        $this->button = $type;
        return $this;
    }

    /**
     * 显示隐藏
     * @param callable $callback
     * @return $this
     */
    public function show(callable $callback): self
    {
        $this->show = $callback;
        return $this;
    }

    /**
     * 自定义权限
     * @param $name
     * @return $this
     */
    public function can($name): self
    {
        if (strpos($name, '.') !== false) {
            $this->auth = $name;
        } else {
            $this->auth = $this->route . '|' . $name;
        }
        return $this;
    }

    /**
     * @param $data
     * @return string
     */
    public function render($data = null): string
    {
        if (!$this->isAuth()) {
            return '';
        }

        if ($this->show && !call_user_func($this->show, $data)) {
            return '';
        }
        $this->class('inline-flex items-center');
        $url = 'javascript:;';
        if ($this->route || $this->params) {
            $params = [];
            if (!$data) {
                $params = $this->params;
            } else {
                foreach ($this->params as $k => $v) {
                    $params[$k] = Tools::parsingArrData($data, $v, true);
                }
            }
            $url = route($this->route, $params);
        }

        switch ($this->type) {
            case 'default':
                $this->attr('href', $url);
                break;
            case 'dialog':
                $this->attr('href', 'javascript:;');
                $this->attr('data-js', 'dialog-open');
                $this->attr('data-url', $url);
                $this->attr('data-title', $this->name);
                break;
            case 'ajax':
                $this->attr('href', 'javascript:;');
                $this->attr('data-js', 'dialog-ajax');
                $this->attr('data-url', $url);
                $this->attr('data-title', '确认进行' . $this->name . '操作?');
                break;
        }
        foreach ($this->typeConfig as $key => $vo) {
            $this->attr('data-' . $key, $vo);
        }

        if ($this->button) {
            $this->class('btn-' . $this->button);
        } else {
            $this->class('text-blue-900 hover:underline');
        }
        $icon = '';
        if ($this->icon) {
            $icon = '<div class="w-4 h-4 mr-2 ">' . \Duxravel\Core\UI\Widget::icon($this->icon) . '</div>';
        }
        return <<<HTML
            <a {$this->toElement()}>$icon $this->name</a>
        HTML;

    }

    private function isAuth()
    {
        $public = app('router')->getRoutes()->getByName($this->route)->getAction('public');
        $app = \Str::before($this->route, '.');
        if ($app <> app()->make('purview_app') || $public) {
            return true;
        }
        $purview = app()->make('purview');
        if (!$purview) {
            return true;
        }
        if ($this->auth) {
            if (!in_array($this->auth, $purview)) {
                return false;
            }
            return true;
        }
        if (\Str::afterLast($this->route, '.') === 'page') {
            if ($this->params['id']) {
                $this->can('edit');
            } else {
                $this->can('add');
            }
        }
        $filterPurview = [];
        foreach ($purview as $vo) {
            $arr = explode('|', $vo);
            $filterPurview[] = $arr[0];
        }
        if (in_array($this->route, $filterPurview)) {
            return true;
        }
        return false;
    }

}

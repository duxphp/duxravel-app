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
    protected string $size = 'medium';
    protected string $model = '';
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

    /**
     * 数据模型
     * @param bool $model
     */
    public function model($model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * 图标
     * @param bool $model
     */
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
    public function button(string $type = 'primary', string $size = 'medium'): self
    {
        $this->button = $type;
        $this->size = $size;
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
     * @return array
     */
    public function render($data = null)
    {
        if (!$this->model && !$this->isAuth()) {
            return [];
        }

        if ($this->show && !call_user_func($this->show, $data)) {
            return [];
        }

        $url = '';
        if (!$this->model && ($this->route || $this->params)) {
            $params = [];
            if (!$data) {
                $params = $this->params;
            } else {
                foreach ($this->params as $k => $v) {
                    $params[$k] = Tools::parsingArrData($data, $v, true);
                }
            }
            $url = route($this->route, $params, false);
        }

        $object = [
            'nodeName' => 'route',
        ];

        switch ($this->type) {
            case 'default':
                $object['href'] = $url;
                break;
            case 'dialog':
                $object['href'] = $url;
                $object['type'] = 'dialog';
                $object['title'] = $this->name;
                break;
            case 'ajax':
                $object['href'] = $url;
                $object['type'] = 'ajax';
                $object['title'] = '确认进行' . $this->name . '操作?';
                break;
        }

        if ($this->model) {
            unset($object['href']);
            $object['vBind:href'] = $this->model . "['$this->route']";
        }

        $object = array_merge($object, $this->typeConfig);

        $name = $this->name;

        if ($this->button) {
            $link = [
                'nodeName' => 'n-button',
                'type' => $this->button,
                'size' => $this->size,
                'child' => [
                    $name
                ]
            ];
            if ($this->icon) {
                $link['child'][] = (new Icon($this->icon))->attr('vSlot:icon', '')->getRender();
            }
        } else {
            $link = [
                'nodeName' => 'div',
                'class' => 'text-blue-600 hover:underline',
                'child' => [
                    $name
                ]
            ];
            if ($this->icon) {
                $link['child'][] = (new Icon($this->icon))->class('mr-2')->getRender();
            }
        }

        $object['child'] = $link;

        return $object;

    }

    private function isAuth()
    {
        if (!\Route::has($this->route)) {
            return false;
        }
        $public = \Route::getRoutes()->getByName($this->route)->getAction('public');
        $app = \Str::before($this->route, '.');
        if ($app <> app()->make('purview_app') || $public) {
            return true;
        }
        $purview = app()->make('purview');
        if (!$purview) {
            return true;
        }
        if (\Str::afterLast($this->route, '.') === 'page') {
            if ($this->params['id']) {
                $this->can('edit');
            } else {
                $this->can('add');
            }
        }
        if ($this->auth) {
            if (!in_array($this->auth, $purview)) {
                return false;
            }
            return true;
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

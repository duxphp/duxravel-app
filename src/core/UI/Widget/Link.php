<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\Facades\Permission;
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
    protected string $url;
    protected array $params = [];
    protected array $fields = [];
    protected ?\Closure $show = null;
    protected string $button = '';
    protected string $type = 'default';
    protected string $status = 'normal';
    protected bool $block = false;
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
     * @param $params
     * @return $this
     */
    public function fields($params)
    {
        $this->fields = $params;
        return $this;
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
     * 获取类型
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 获取类型配置
     * @return array
     */
    public function getTypeConfig()
    {
        return $this->typeConfig;
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
     * class
     * @param bool $model
     */
    public function class($name): self
    {
        $this->class[] = $name;
        return $this;
    }

    /**
     * 设置为按钮
     * @param string $type
     * @return $this
     */
    public function button(string $type = 'primary', string $status = 'medium', bool $block = false): self
    {
        $this->button = $type;
        $this->status = $status;
        $this->block = $block;
        return $this;
    }

    /**
     * 获取路由
     * @return $this
     */
    public function getRoute(): string
    {
        return $this->route;
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
     * 获取url
     * @return false|string
     */
    public function getUrl()
    {
        if (!$this->isAuth()) {
            return false;
        }

        if ($this->show && !call_user_func($this->show)) {
            return false;
        }

        return app_route($this->route, $this->params, false, $this->model, $this->fields);
    }

    /**
     * @return array
     */
    public function render()
    {
        $url = $this->getUrl();

        if (!$url) {
            return [];
        }

        $object = [
            'nodeName' => 'route',
            'name' => $this->name
        ];

        switch ($this->type) {
            case 'default':
                $object['vBind:href'] = $url;
                break;
            case 'dialog':
                $object['vBind:href'] = $url;
                $object['type'] = 'dialog';
                $object['title'] = $this->name;
                break;
            case 'ajax':
                $object['vBind:href'] = $url;
                $object['type'] = 'ajax';
                $object['title'] = '确认进行' . $this->name . '操作?';
                break;
        }
        $object = array_merge($object, $this->typeConfig);

        if ($this->button) {
            $link = [
                'nodeName' => 'a-button',
                'class' => implode(' ', $this->class),
                'type' => $this->button,
                'status' => $this->status,
                'child' => [
                    $this->name
                ]
            ];
            if ($this->icon) {
                $link['child'][] = (new Icon($this->icon))->attr('vSlot:icon', '')->getRender();
            }
            if ($this->block) {
                $link['long'] = true;
            }
        } else {
            $link = [
                'nodeName' => 'span',
                'class' => 'arco-link arco-link-status-normal ' . implode(' ', $this->class),
                'child' => [
                    $this->name
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
        // 路由不存在
        if (!\Route::has($this->route)) {
            return false;
        }
        // 验证是否公共类
        $public = \Route::getRoutes()->getByName($this->route)->getAction('public');
        if ($public) {
            return true;
        }
        // 验证是否当前守护器
        $app = \Str::before($this->route, '.');
        if ($app <> Permission::getGuerd()) {
            return true;
        }

        // 设置通用页面权限
        if (\Str::afterLast($this->route, '.') === 'page') {
            if ($this->params['id']) {
                $this->can('edit');
            } else {
                $this->can('add');
            }
        }

        // 验证自定义权限
        if ($this->auth) {
            if (!auth($app)->user()->can($this->auth)) {
                return false;
            }
            return true;
        }

        // 验证通用权限
        if (auth($app)->user()->can($this->route)) {
            return true;
        }
        return false;
    }

}

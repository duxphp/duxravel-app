<?php

namespace Duxravel\Core\UI;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Duxravel\Core\Model\ModelAgent;
use Duxravel\Core\Util\View;

/**
 * 表单UI
 * Class Form
 * @package Duxravel\Core\UI
 * @method Form\Cascader cascader(string $name, string $field, $data = null, string $has = '')
 * @method Form\CheckBox checkbox(string $name, string $field, $data = null, string $has = '')
 * @method Form\Choice choice(string $name, string $field, string $has = '')
 * @method Form\Color color(string $name, string $field, string $has = '')
 * @method Form\Data data(string $name, string $field, string $has = '')
 * @method Form\Date date(string $name, string $field, string $has = '')
 * @method Form\Daterange daterange(string $name, string $field, string $has = '')
 * @method Form\Datetime datetime(string $name, string $field, string $has = '')
 * @method Form\Editor editor(string $name, string $field, string $has = '')
 * @method Form\Email email(string $name, string $field, string $has = '')
 * @method Form\File file(string $name, string $field, string $has = '')
 * @method Form\Image image(string $name, string $field, string $has = '')
 * @method Form\Images images(string $name, string $field, string $has = '')
 * @method Form\Ip ip(string $name, string $field, string $has = '')
 * @method Form\Password password(string $name, string $field, string $has = '')
 * @method Form\Radio radio(string $name, string $field, $data = null, string $has = '')
 * @method Form\Select select(string $name, string $field, $data = null, string $has = '')
 * @method Form\Tel tel(string $name, string $field, string $has = '')
 * @method Form\Text text(string $name, string $field, string $has = '')
 * @method Form\Time time(string $name, string $field, string $has = '')
 * @method Form\Url url(string $name, string $field, string $has = '')
 */
class Form
{
    public $model;
    public $modelElo;
    public $info;
    protected $title;
    protected $bark;
    protected $attr;
    protected $extend;
    protected string $method = 'post';
    protected string $action = '';
    protected array $keys = [];
    protected array $row = [];
    protected array $flow = [];
    protected array $assign = [];
    protected array $script = [];
    protected bool $dialog = false;
    protected Collection $element;

    /**
     * Form constructor.
     * @param $data
     * @param bool $model
     */
    public function __construct($data = null, bool $model = true)
    {

        if (!$model) {
            // 虚拟数据
            $this->info = $data;
        } else {
            // 数据模型
            if ($data instanceof Eloquent) {
                $this->model = new ModelAgent($data);
                $this->modelElo = $data;
            } else {
                $this->info = $data;
            }
        }
        $this->element = Collection::make();
    }

    /**
     * 设置条件主键
     * @param $key
     * @param $value
     */
    public function setKey($key, $value): void
    {
        if ($key && $value) {
            $this->keys[$key] = $value;
        }
        if (!$this->model) {
            return;
        }
        $this->setInfo();
    }

    /**
     * 获取当前数据
     * @return array|Eloquent
     */
    public function info()
    {
        return $this->info;
    }

    /**
     * 模型对象
     * @return ModelAgent
     */
    public function model(): \Duxravel\Core\Model\ModelAgent
    {
        return $this->model;
    }

    /**
     * 模型对象
     * @return Eloquent
     */
    public function modelElo(): ?Eloquent
    {
        return $this->modelElo;
    }

    /**
     * 获取元素集合
     * @return Collection
     */
    public function getElement(): Collection
    {
        return $this->element;
    }

    /**
     * 表单标题
     * @param $title
     * @param bool $back
     * @return $this
     */
    public function title($title, bool $back = true): self
    {
        $this->title = $title;
        $this->bark = $back;
        return $this;
    }

    /**
     * 多行组件
     * @return Form\Row
     */
    public function row(): Form\Row
    {
        $data = new Form\Row();
        $this->element->push($data);
        return $data;
    }

    /**
     * 切换组件
     * @return Form\Tab
     */
    public function tab(): Form\Tab
    {
        $data = new Form\Tab();
        $this->element->push($data);
        return $data;
    }

    /**
     * 附加脚本
     * @param $content
     * @return $this
     */
    public function script($content): Form
    {
        $this->script[] = $content;
        return $this;
    }

    /**
     * 附加属性
     * @param $name
     * @param $value
     * @return $this
     */
    public function attr($name, $value): Form
    {
        $this->attr[] = $name . '="' . $value . '"';
        return $this;
    }

    /**
     * 卡片组件
     * @param $callback
     * @return Form\Card
     */
    public function card($callback): Form\Card
    {
        $data = new Form\Card($callback);
        $this->element->push($data);
        return $data;
    }

    /**
     * Html内容
     * @param $name
     * @param $callback
     * @return Form\Html
     */
    public function html($name, $callback): Form\Html
    {
        $data = new Form\Html($name, $callback);
        $this->element->push($data);
        return $data;
    }

    /**
     * 布局组件
     * @param $callback
     * @return Form\Layout
     */
    public function layout($callback): Form\Layout
    {
        $data = new Form\Layout($callback);
        $this->element->push($data);
        return $data;
    }

    /**
     * 表单渲染
     * @param $info
     * @return string
     */
    public function renderForm($info): string
    {
        $forms = $this->element->map(function ($vo, $key) use ($info) {
            $sort = $vo->getSort();
            $sort = $sort ?? $key;
            if ($vo instanceof Form\Composite) {
                return [
                    'name' => $vo->getName(),
                    'layout' => $vo->getLayout(),
                    'group' => $vo->getGroup(),
                    'help' => $vo->getHelp(),
                    'helpLine' => $vo->getHelpLine(),
                    'prompt' => $vo->getPrompt(),
                    'must' => $vo->getMust(),
                    'html' => $vo->render($info),
                    'sort' => $sort,
                ];
            }
            return [
                'name' => $vo->getName(),
                'layout' => $vo->getLayout(),
                'group' => $vo->getGroup(),
                'help' => $vo->getHelp(),
                'helpLine' => $vo->getHelpLine(),
                'prompt' => $vo->getPrompt(),
                'must' => $vo->getMust(),
                'html' => $vo->render(Tools::parsingArrData($info, $vo->getHas() ?: $vo->getField())),
                'sort' => $sort,
            ];
        })->sortBy('sort');

        return view('Common.UI.View.Form.layout', ['items' => $forms])->render();
    }

    /**
     * 设置表单内容
     */
    public function setInfo()
    {
        if ($this->info) {
            return $this->info;
        }
        if ($this->keys) {
            $model = $this->model();
            foreach ($this->keys as $key => $value) {
                $model->where($key, $value);
            }
            $info = $model->eloquent()->first();
            if (empty($info)) {
                app_error('内容不存在');
            }
        } else {
            $info = [];
        }
        $this->info = $info;
    }

    /**
     * 提交类型
     * @param string $name
     * @return $this
     */
    public function method(string $name = 'post'): self
    {
        $this->method = $name;
        return $this;
    }

    /**
     * 指定模板变量
     * @param string $name
     * @param null $value
     * @return $this
     */
    public function assign(string $name, $value = null): self
    {
        $this->assign[$name] = $value;
        return $this;
    }

    /**
     * 是否弹窗
     * @param $status
     * @return $this
     */
    public function dialog($status): self
    {
        $this->dialog = (bool)$status;
        if ($this->dialog) {
            $this->attr('data-success-notify', 'false');
            $this->attr('data-jump', 'false');
        }
        return $this;
    }

    /**
     * 保存链接
     * @param $uri
     * @return $this
     */
    public function action($uri): self
    {
        $this->action = $uri;
        return $this;
    }

    /**
     * 渲染表单
     */
    public function render()
    {
        // 提交地址
        if ($this->action) {
            $action = $this->action;
        } else {
            $params = [];
            if ($this->modelElo) {
                $key = $this->modelElo->getKeyName();
                $id = $this->info->$key;
                $params['id'] = $id;
            }
            $action = route(\Str::beforeLast(request()->route()->getName(), '.') . '.' . 'save', $params);
        }

        // 渲染表单元素
        $formHtml = $this->renderForm($this->info);

        // 处理附加js
        $script = [];
        foreach ($this->script as $vo) {
            if ($vo instanceof \Closure) {
                $script[] = $vo($this);
            } else {
                $script[] = $vo;
            }
        }

        // 渲染表单页面
        $assign = [
            'title' => $this->title,
            'back' => $this->bark,
            'keys' => $this->keys,
            'dialog' => $this->dialog,
            'formHtml' => $formHtml,
            'action' => $action,
            'method' => $this->method,
            'attr' => $this->attr,
            'script' => $script,
        ];
        $assign = array_merge($assign, $this->assign);

        if ($this->dialog) {
            return (new View('Common.UI.View.form', $assign))->render('dialog');
        }
        return (new View('Common.UI.View.form', $assign))->render();
    }

    /**
     * 提交数据处理
     * @param $time
     * @return Collection
     * @throws ValidationException
     */
    public function getData($time): Collection
    {
        // 获取提交数据
        $data = request()->input();

        // 提交数据处理
        if ($this->flow['submit']) {
            $data = call_user_func($this->flow['submit'], $data, $time);
        }
        // 过滤数据
        $collection = Collection::make();
        $this->element->map(function ($item) use ($collection, $time) {
            $inputs = $item->getInput($time);
            $filed = $item->getField();
            if ($item instanceof Form\Composite) {
                foreach ($inputs as $key => $vo) {
                    $collection->put($key, $vo);
                }
            } elseif ($filed) {
                $collection->put($filed, $inputs);
            }
        });

        //验证数据
        $rules = [];
        $msgs = [];
        $collection->map(function ($item) use (&$rules, &$msgs) {
            if ($item['verify']['rule']) {
                $rules = $rules + $item['verify']['rule'];
            }
            if ($item['verify']['msg']) {
                $msgs = $msgs + $item['verify']['msg'];
            }
        });
        if ($rules) {
            \Validator::make($data, $rules, $msgs)->validate();
        }

        // 格式化数据
        return $collection->map(function ($item) {
            $value = $item['value'];
            if ($item['format']) {
                foreach ($item['format'] as $vo) {
                    $value = call_user_func($vo, $item['value']);
                }
            }
            return ['value' => $value, 'has' => $item['has']];
        });
    }

    /**
     * 流程时间
     * @var array
     */
    protected array $prepared = [];

    /**
     * 保存数据
     * @return Collection|mixed|\Tightenco\Collect\Support\Collection
     * @throws ValidationException
     * @throws \Throwable
     */
    public function save()
    {
        // 获取主键数据
        $id = 0;
        if ($this->modelElo) {
            $id = $this->keys[$this->modelElo->getKeyName()];
        }

        // 保存类型
        $type = $id ? 'edit' : 'add';

        // 获取提交数据
        $data = $this->getData($type);

        // 提取提交数据
        $formatData = [];
        foreach ($data as $key => $vo) {
            $formatData[$key] = $vo['value'];
        }
        $formatData = collect($formatData);

        // 非模型返回集合
        if (!$this->modelElo) {
            return $formatData;
        }

        // 获取模型对象
        if ($type === 'add') {
            $model = $this->modelElo;
        } else {
            $model = $this->modelElo->find($id);
        }

        // 保存数据库
        DB::transaction(function () use ($model, $data, $type, $formatData) {
            $data->map(function ($item, $key) use ($model) {
                $has = $item['has'];
                // 查询关联对象
                if (method_exists($model, $has)) {
                    $relation = $model->$has();
                    // 多对多
                    if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        $this->prepared[] = static function ($model) use ($item) {
                            $model->{$item['has']}()->sync($item['value']);
                        };
                    }
                } elseif (\Schema::hasColumn($model->getTable(), $key)) {
                    // 过滤无用字段
                    $model->$key = $item['value'];
                }
            });

            // 保存前置回调
            if ($this->flow['before']) {
                $ret = call_user_func($this->flow['before'], $formatData, $type, $model);
                if ($ret instanceof Eloquent) {
                    $model = $ret;
                }
            }

            $model->save();

            // 同步关联数据
            foreach ($this->prepared as $callback) {
                $callback($model);
            }
            // 保存后置回调
            if ($this->flow['after']) {
                call_user_func($this->flow['after'], $formatData, $type, $model);
            }
        });
        return $model->getKey();
    }

    /**
     * 提交之前回调
     * @param $callback
     * @return $this
     */
    public function submit($callback): Form
    {
        $this->flow['submit'] = $callback;
        return $this;
    }

    /**
     * 保存之前回调
     * @param $callback
     * @return $this
     */
    public function before($callback): Form
    {
        $this->flow['before'] = $callback;
        return $this;
    }

    /**
     * 保存后回调
     * @param $callback
     * @return $this
     */
    public function after($callback): Form
    {
        $this->flow['after'] = $callback;
        return $this;
    }

    /**
     * 扩展元素
     * @param $method
     * @param $className
     */
    public function extend($method, $className): void
    {
        $this->extend[$method] = $className;
    }

    /**
     * 回调类库
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        $class = '\\Modules\\Common\UI\\Form\\' . ucfirst($method);
        if (!class_exists($class)) {
            if (!$this->extend[$method]) {
                throw new \Exception('There is no form method "' . $method . '"');
            } else {
                $class = $this->extend[$method];
            }
        }
        $object = new $class(...$arguments);
        $this->element->push($object);
        return $object;
    }

}

<?php

namespace Duxravel\Core\UI;

use Duxravel\Core\Exceptions\ErrorException;
use Duxravel\Core\UI\Form\Node;
use Duxravel\Core\UI\Widget\Icon;
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
 *
 * @method Form\Area area(string $name, array $map = [], string $has = '')
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
 * @method Form\Files files(string $name, string $field, string $has = '')
 * @method Form\Image image(string $name, string $field, string $has = '')
 * @method Form\Images images(string $name, string $field, string $has = '')
 * @method Form\Ip ip(string $name, string $field, string $has = '')
 * @method Form\Location location(string $name, string $field, string $has = '')
 * @method Form\Password password(string $name, string $field, string $has = '')
 * @method Form\Radio radio(string $name, string $field, $data = null, string $has = '')
 * @method Form\Select select(string $name, string $field, $data = null, string $has = '')
 * @method Form\Tree tree(string $name, string $field, $data = null, string $has = '')
 * @method Form\TreeSelect treeSelect(string $name, string $field, $data = null, string $has = '')
 * @method Form\Toggle toggle(string $name, string $field, string $has = '')
 * @method Form\Tags tags(string $name, string $field, string $has = '')
 * @method Form\Tel tel(string $name, string $field, string $has = '')
 * @method Form\Text text(string $name, string $field, string $has = '')
 * @method Form\Textarea textarea(string $name, string $field, string $has = '')
 * @method Form\Number number(string $name, string $field, string $has = '')
 * @method Form\Time time(string $name, string $field, string $has = '')
 * @method Form\Url url(string $name, string $field, string $has = '')
 */
class Form
{
    public $model;
    public $modelElo;
    public $info;
    protected string $title = '';
    protected bool $back = true;
    protected array $attr = [];
    protected array $extend = [];
    protected string $method = 'post';
    protected string $action = '';
    protected array $keys = [];
    protected array $row = [];
    protected array $flow = [];
    protected array $assign = [];
    protected array $script = [];
    protected array $scriptReturn = [];
    protected array $sideNode = [];
    protected bool $dialog = false;
    protected bool $vertical = true;
    protected array $map = [];
    public Collection $element;
    private ?array $bottom = null;
    protected array $statics = [];
    protected bool $debug = false;

    /**
     * Form constructor.
     * @param      $data
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

        if (request()->header('x-dialog')) {
            $this->dialog = true;
        }
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
     * @param null $class
     */
    public function getElement($class = null, $num = 0): Collection
    {
        if ($class) {
            $i = 0;
            foreach ($this->element as $vo) {
                if ($vo instanceof $class) {
                    if ($i === $num) {
                        return $vo;
                    }
                    $i++;
                }
            }
        }
        return $this->element;
    }

    /**
     * 表单标题
     * @param string $title
     * @param bool   $back
     * @return $this
     */
    public function title(string $title, bool $back = true): self
    {
        $this->title = $title;
        $this->back = $back;
        return $this;
    }

    /**
     * 附加脚本
     * @param string $content
     * @param string $return
     * @return $this
     */
    public function script(string $content = '', string $return = ''): self
    {
        $this->script[] = $content;
        $this->scriptReturn[] = $return;
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
     * 多行组件
     * @return Form\Row
     */
    public function row(): Form\Row
    {
        $data = new Form\Row();
        $data->dialog($this->dialog);
        $data->vertical($this->vertical);
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
        $data->dialog($this->dialog);
        $data->vertical($this->vertical);
        $this->element->push($data);
        return $data;
    }

    /**
     * 卡片组件
     * @param $callback
     * @return Form\Card
     */
    public function card($callback): Form\Card
    {
        $data = new Form\Card($callback);
        $data->dialog($this->dialog);
        $data->vertical($this->vertical);
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
        $data->dialog($this->dialog);
        $data->vertical($this->vertical);
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
        $data->dialog($this->dialog);
        $this->element->push($data);
        return $data;
    }

    // 边栏元素
    public function side($callback, string $direction = 'left'): self
    {
        $this->sideNode[] = [
            'callback' => $callback,
            'direction' => $direction
        ];
        return $this;
    }

    /**
     * 前端静态覆盖数据
     * @param string|array $statics
     * @param string $key stype|css|scriptString|script
     * @return $this
     */
    public function statics($statics,string $key = 'style'): self
    {
        $this->statics[$key] = array_merge($this->statics[$key] ?? [],is_array($statics) ? $statics : [$statics]);
        return $this;
    }

    /**
     * 弹框宽度
     * @param string $width
     * @return $this
     */
    public function width(string $width): self
    {
        $this->statics(".page-dialog{width: {$width};max-width:none;}");
        return $this;
    }

    /**
     * 设置字段映射
     * @param array $map
     * @return $this
     */
    public function map(array $map): self
    {
        $this->map = array_merge($this->map, $map);
        return $this;
    }

    /**
     * 获取表单数据
     */
    public function renderData($info)
    {
        $collection = Collection::make();
        $this->element->map(function ($item) use ($collection, $info) {
            $data = $item->getData($info);
            foreach ($data as $key => $vo) {
                $collection->put($key, $vo);
            }
        });
        if ($this->map) {
            foreach ($this->map as $k => $v) {
                $key = is_int($k) ? str_replace(['.', '->'], '_', $v) : $k;
                $vo = is_callable($v) ? call_user_func($v, $info) : Tools::parsingArrData($info,$v);
                $collection->put($key, $vo);
            }
        }
        return $collection->toArray();
    }

    /**
     * @return array
     */
    public function renderForm(): array
    {
        return $this->element->map(function ($vo, $key) {
            $sort = $vo->getSort();
            $sort = $sort ?? $key;

            $groupRule = $vo->getGroup();
            $group = [];
            foreach ($groupRule as $rule) {
                if (is_array($rule['value'])) {
                    $value = json_encode($rule['value']);
                    $group[] = "{$value}.indexOf(data.{$rule['name']}) !== -1";
                }else {
                    $group[] = "data.{$rule['name']} ==  '{$rule['value']}'";
                }

            }
            $group = $group ? implode(' || ', $group) : null;

            if ($vo instanceof Form\Composite) {
                $node = [
                    'nodeName' => 'div',
                    'child' => $vo->getRender(),
                    'sort' => $sort,
                ];
                if ($group) {
                    $node['vIf'] = $group;
                }

                return array_merge($node, $vo->getLayoutAttr());
            }

            $helpNode = [];
            $prompt = $vo->getPrompt();
            $help = $vo->getHelp();
            if ($prompt) {
                $helpNode = [
                    'nodeName' => 'a-tooltip',
                    'class' => 'ml-3',
                    'position' => 'top',
                    'content' => $vo->getPrompt(),
                    'child' => [
                        'nodeName' => 'span',
                        'child' => [
                            'nodeName' => 'icon-question-circle'
                        ]
                    ],
                ];
            }
            if ($help) {
                $helpNode = [
                    'nodeName' => 'div',
                    'class' => 'text-gray-300 pt-2 pb-2 ml-3',
                    'child' => $help
                ];
            }

            $helpLine = $vo->getHelpLine();
            $must = $vo->getMust();

            $item = [
                'nodeName' => 'a-form-item',
                'label' => $vo->getName(),
                'field' => $vo->getField(),
                'vIf' => $group,
                'sort' => $sort,
                'child' => [
                    $vo->getRender(),
                    $helpLine ? [
                        'vSlot:help' => '',
                        'nodeName' => 'div',
                        'child' => $helpLine
                    ] : [],
                    $helpNode ? [
                        'nodeName' => 'div',
                        'class' => 'ml-2',
                        'child' => $helpNode
                    ] : []
                ]
            ];

            if ($must) {
                $item['rules'] = [
                    [
                        'required' => true,
                        'message' => '请填写' . $vo->getName()
                    ]
                ];
            }
            return $item;
        })->filter()->sortBy('sort')->values()->toArray();
    }

    /**
     * @return mixed|void|null
     * @throws ErrorException
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
     * @param null   $value
     * @return $this
     */
    public function assign(string $name, $value = null): self
    {
        $this->assign[$name] = $value;
        return $this;
    }

    /**
     * 是否弹窗
     * @param bool $status
     * @return $this
     */
    public function dialog(bool $status): self
    {
        $this->dialog = $status;
        $this->vertical = true;
        return $this;
    }

    /**
     * 纵向表单
     * @param bool $status
     * @return $this
     */
    public function vertical(bool $status): self
    {
        $this->vertical = $status;
        return $this;
    }

    /**
     * 获取弹窗状态
     * @return bool
     */
    public function getDialog(): bool
    {
        return $this->dialog;
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
     * 底部组件
     * @param array|null $bottom
     * @return $this
     */
    public function bottom(?array $bottom): self
    {
        $this->bottom = $bottom;
        return $this;
    }

    /**
     * 调试
     * @param bool $debug
     * @return $this
     */
    public function debug(bool $debug = true): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * 渲染表单
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function render()
    {
        return app_success('ok', $this->renderArray());
    }

    /**
     * 渲染表单(数组)
     * @return array
     */
    public function renderArray()
    {
        $node = $this->renderNode();
        return $node->render();
    }

    /**
     * 只渲染form
     * @return array
     */
    public function renderFormCore()
    {
        $node = $this->renderNode();
        return $node->renderFormCore();
    }

    /**
     * 渲染组件
     * @return Node
     */
    public function renderNode()
    {
        // 提交地址
        if ($this->action) {
            $action = $this->action;
        } else {
            $params = request()->all();
            $routeParams = request()->route()->parameters();
            $params = array_merge($params, $routeParams);
            if ($this->modelElo) {
                $key = $this->modelElo->getKeyName();
                $id = $this->info->$key;
                $params['id'] = $id;
            }else {
                foreach ($this->keys as $name => $value) {
                    $params[$name] = $value;
                }
            }
            $action = route(\Str::beforeLast(request()->route()->getName(), '.') . '.' . 'save', $params);
        }

        $node = new Node($action, $this->method, $this->title);
        $node->dialog($this->dialog);
        $node->debug($this->debug);
        $node->vertical($this->vertical);
        $node->back($this->back);

        // 表单元素·
        $node->element($this->renderForm());

        $node->bottom($this->bottom);
        $node->statics($this->statics);

        // 表单数据
        $node->data($this->renderData($this->info));

        // 边栏元素
        foreach ($this->sideNode as $vo) {
            $node->side($vo['callback'], $vo['direction']);
        }

        // 处理附加js
        foreach ($this->script as $key => $value) {
            $node->script($value, $this->scriptReturn[$key]);
        }

        return $node;
    }

    /**
     * 获取提交数据
     * @param $time
     * @param array|null $data
     * @return Collection
     */
    public function getInput($time,?array $data = null): Collection
    {
        // 获取提交数据
        if(is_null($data)){
            $data = request()->input();
        }

        // 提交数据处理
        if ($this->flow['submit']) {
            foreach ($this->flow['submit'] as $item) {
                $data = $item($data, $time);
            }
        }
        // 过滤数据
        $collection = Collection::make();
        $this->element->map(function ($item) use ($collection, $time) {
            $inputs = $item->getInput($time);

            foreach ($inputs as $key => $vo) {
                $collection->put($key, $vo);
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
        $validator = \Validator::make($data, $rules, $msgs);

        if ($this->flow['validator']) {
            foreach ($this->flow['validator'] as $vo) {
                $vo($validator);
            }
        }
        $validator->validate();

        // 格式化数据
        return $collection->map(function ($item) {
            $value = $item['value'];
            if ($item['format']) {
                foreach ($item['format'] as $vo) {
                    $value = call_user_func($vo, $item['value']);
                }
            }
            return ['value' => $value, 'has' => $item['has'], 'pivot' => $item['pivot']];
        });
    }

    /**
     * 流程时间
     * @var array
     */
    protected array $prepared = [];

    /**
     * 主键值
     * @var null
     */
    public $modelId = null;

    /**
     * 保存数据
     * @return null $modelId
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
        $data = $this->getInput($type);

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

            // 保存前置回调
            if ($this->flow['front']) {
                foreach ($this->flow['front'] as $item) {
                    $ret = $item($formatData, $type, $model);
                    if ($ret instanceof Eloquent) {
                        $model = $ret;
                    }
                }
            }

            // 树形处理 已废弃 先设置 scoped 数据 再设置上级数据
            /*if ($model->parent_id) {
                if (method_exists($model, 'appendToNode')) {
                    $model = $model->appendToNode($this->modelElo->find($formatData['parent_id']));
                }
            }*/

            $data->map(function ($item, $key) use ($model) {
                $has = $item['has'];
                // 查询关联对象
                if (method_exists($model, $has) && !is_null($item['value'])) {
                    $relation = $model->$has();
                    // 多对多
                    if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        $this->prepared[] = static function ($model) use ($item) {
                            $sync = is_array($item['value']) ? $item['value'] : [$item['value']];
                            $syncFormat = [];
                            if ($item['pivot']) {
                                foreach ($sync as $vo) {
                                    $syncFormat[$vo] = $item['pivot'];
                                }
                                $sync = $syncFormat;
                            }
                            $model->{$item['has']}()->sync($sync);
                        };
                    }
                } else if (\Schema::hasColumn($model->getTable(), $key)) {
                    // 过滤无用字段
                    $model->$key = $item['value'];
                }
            });

            // 保存前置回调
            if ($this->flow['before']) {
                foreach ($this->flow['before'] as $item) {
                    $ret = $item($formatData, $type, $model);
                    if ($ret instanceof Eloquent) {
                        $model = $ret;
                    }
                }
            }

            $model->save();

            // 同步关联数据
            foreach ($this->prepared as $callback) {
                $callback($model);
            }
            // 保存后置回调
            if ($this->flow['after']) {
                foreach ($this->flow['after'] as $item) {
                    $item($formatData, $type, $model);
                }
            }
        });
        $this->modelId = $model->getKey();
        return $this->modelId;
    }

    /**
     * 处理数据之前提交
     * @param $callback
     * @return $this
     */
    public function front($callback): Form
    {
        $this->flow['front'][] = $callback;
        return $this;
    }

    /**
     * 验证表单扩展
     * @param $callback
     * @return $this
     */
    public function validator($callback): Form
    {
        $this->flow['validator'][] = $callback;
        return $this;
    }

    /**
     * 提交之前回调
     * @param $callback
     * @return $this
     */
    public function submit($callback): Form
    {
        $this->flow['submit'][] = $callback;
        return $this;
    }

    /**
     * 保存之前回调
     * @param $callback
     * @return $this
     */
    public function before($callback): Form
    {
        $this->flow['before'][] = $callback;
        return $this;
    }

    /**
     * 保存后回调
     * @param $callback
     * @return $this
     */
    public function after($callback): Form
    {
        $this->flow['after'][] = $callback;
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
     * 前端事件
     * @param $table
     * @param $name
     * @param $type
     * @return array|false
     */
    public function callbackEvent($table, $name, $type, $data = null)
    {
        if (!$this->modelId) {
            return false;
        }
        $rowsData = $data ?: $this->modelElo->where($this->modelElo->getKeyName(), $this->modelId)->get();
        $list = $table->renderRowData($rowsData, false);

        $parentKey = null;
        if ($table->getTree()) {
            $parentKey = $this->modelElo->find($this->modelId)->parent_id;
        }

        $event = new Event($name);
        foreach ($list as $item) {
            $event->add($type, $this->modelId, $item, $parentKey !== false ? ['parentKey' => $parentKey] : []);
        }
        return $event->render();

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
        $class = '\\Duxravel\\Core\\UI\\Form\\' . ucfirst($method);
        if (!class_exists($class)) {
            if (!$this->extend[$method]) {
                throw new \Exception('There is no form method "' . $method . '"');
            }
            $class = $this->extend[$method];
        }
        $object = new $class(...$arguments);
        $object->dialog($this->dialog);
        $this->element->push($object);
        return $object;
    }

}

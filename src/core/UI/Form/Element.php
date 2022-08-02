<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Tools;

/**
 * 单元素组件
 * Class Component
 * @package Duxravel\Core\UI
 */
class Element
{
    protected string $name = '';
    protected string $field = '';
    protected string $has = '';
    protected $help = '';
    protected $helpLine = '';
    protected string $prompt = '';
    protected string $model = 'data.';
    protected array $class = [];
    protected $replace = '';
    protected array $attr = [];
    protected array $layoutAttr = [];
    protected array $style = [];
    protected array $pivot = [];
    protected array $verify = [];
    protected array $verifyMsg = [];
    protected array $format = [];
    protected bool $dialog = false;
    protected bool $vertical = false;
    protected bool $label = true;
    protected bool $component = false;
    protected bool $must = false;
    protected array $group = [];
    protected ?int $sort = null;
    protected $modelElo;
    protected $value;
    protected $default;

    /**
     * @var \Closure|null 自定义回调函数(格式化字段)
     */
    protected ?\Closure $formatFunc = null;

    /**
     * 设置弹窗
     * @param $bool
     * @return $this
     */
    public function dialog($bool): self
    {
        $this->dialog = $bool;
        return $this;
    }

    /**
     * 设置方向
     * @param $bool
     * @return $this
     */
    public function vertical($bool): self
    {
        $this->vertical = $bool;
        return $this;
    }

    /**
     * 设置数据模型
     */
    public function modelElo($class)
    {
        $this->modelElo = $class;
        return $this;
    }

    /**
     * 设置数据前缀
     */
    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * 获取模型字段
     * @return string
     */
    public function getModelField()
    {
        return $this->model . $this->field;
    }

    /**
     * 获取标签状态
     * @return bool
     */
    public function getLabel(): bool
    {
        return $this->label;
    }


    /**
     * 获取字段名
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * 获取关联模型
     * @return string
     */
    public function getHas(): string
    {
        return $this->has;
    }

    /**
     * 获取名称
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 获取值
     * @param $value
     * @return mixed
     */
    public function getValue($value = null)
    {
        return ($this->value ?? $value) ?? $this->default;
    }

    /**
     * 获取数组值
     * @param      $value
     * @param bool $json
     * @return array|null
     */
    public function getValueArray($value, bool $json = false): ?array
    {
        $value = $this->getValue($value);
        if ($value instanceof \Illuminate\Database\Eloquent\Collection) {
            if ($value->count()) {
                $values = $value->pluck($value->first()->getKeyName())->toArray();
            } else {
                $values = $json ? [] : null;
            }
        } else if (is_array($value)) {
            $values = $value;
        } else if ($value !== null) {
            $values = $json ? json_decode($value, true) : explode(',', $value);
        } else {
            $values = $json ? [] : null;
        }
        return $values;
    }

    /**
     * 获取回调数据组
     * @param $data
     * @param $value
     * @return array
     */
    public function getCallbackArray($data, $value): array
    {
        if ($data instanceof \Closure) {
            return call_user_func($data, [$value]);
        }
        if (is_array($data)) {
            return $data;
        }
        return [];
    }

    /**
     * 设置选项值
     * @param $value
     * @return $this
     */
    public function value($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 设置默认值
     * @param $value
     * @return $this
     */
    public function default($value): self
    {
        $this->default = $value;
        return $this;
    }

    /**
     * 设置帮助信息
     * @param string|array $value
     * @param bool         $line
     * @return $this
     */
    public function help($value, bool $line = false): self
    {
        if ($line) {
            $this->helpLine = $value;
        } else {
            $this->help = $value;
        }
        return $this;
    }

    /**
     * 属性数据
     * @param string       $name
     * @param string|array $value
     * @return $this
     */
    public function attr(string $name, $value): self
    {
        $this->attr[$name] = $value;
        return $this;
    }

    /**
     * 布局树形
     * @param string $name
     * @param        $value
     * @return $this
     */
    public function layoutAttr(string $name, $value): self
    {
        $this->layoutAttr[$name] = $value;
        return $this;
    }

    /**
     * 属性数组
     * @param $attr
     * @return $this
     */
    public function attrArray($attr): self
    {
        $this->attr = $attr;
        return $this;
    }

    /**
     * class样式
     * @param string $name
     * @return $this
     */
    public function class(string $name): self
    {
        $this->class[] = $name;
        return $this;
    }

    /**
     * 字符串替换标签(数字字符串处理使用)
     * @param $replace
     * @return $this
     */
    public function replace($replace): self
    {
        $this->replace = $replace;
        return $this;
    }

    /**
     * 设置提示
     * @param $name
     * @return $this
     */
    public function placeholder($name): self
    {
        if ($name) {
            $this->attr['placeholder'] = $name;
        }
        return $this;
    }

    /**
     * 元素分组
     * @param $name
     * @param $value
     * @return $this
     */
    public function group($name, $value): self
    {
        $this->group[] = [
            'name' => $name,
            'value' => $value
        ];
        return $this;
    }

    /**
     * 必填样式
     * @return $this
     */
    public function must(): self
    {
        $this->must = true;
        $this->verify['all'][$this->field][] = 'required';
        $this->verifyMsg['all'][$this->field . '.' . 'required'] = '请输入' . $this->name;
        return $this;
    }

    /**
     * 帮助信息
     * @param $content
     * @return $this
     */
    public function prompt($content): self
    {
        $this->prompt = $content;
        return $this;
    }

    /**
     * 排序
     * @param $num
     * @return $this
     */
    public function sort($num): self
    {
        $this->sort = $num;
        return $this;
    }

    /**
     * 获取分组
     * @return array
     */
    public function getGroup(): array
    {
        return $this->group;
    }

    /**
     * 获取必须
     * @return bool
     */
    public function getMust(): bool
    {
        return $this->must;
    }

    /**
     * 获取提醒
     * @return string
     */
    public function getPrompt(): string
    {
        return $this->prompt;
    }

    /**
     * 获取帮助行
     * @return string|array
     */
    public function getHelpLine()
    {
        return $this->helpLine;
    }


    /**
     * 获取层属性
     * @return array
     */
    public function getLayoutAttr()
    {
        return $this->layoutAttr;
    }

    /**
     * 同步附加数据
     * @param $data
     * @return $this
     */
    public function pivot($data)
    {
        $this->pivot = $data;
        return $this;
    }

    /**
     * 设置字段验证
     * @param        $rule
     * @param array  $msg
     * @param string $time
     * @return $this
     */
    public function verify($rule, array $msg = [], string $time = 'all'): self
    {
        $this->verify[$time][$this->field] = $rule;
        foreach ($msg as $key => $vo) {
            $this->verifyMsg[$time][$this->field . '.' . $key] = $vo;
        }
        return $this;
    }

    /**
     * 获取验证规则
     * @param string $time
     * @return array
     */
    public function getVerify(string $time = 'add'): array
    {
        return [
            'rule' => (array)$this->verify['all'] + (array)$this->verify[$time],
            'msg' => (array)$this->verifyMsg['all'] + (array)$this->verifyMsg[$time]
        ];
    }

    /**
     * 设置表单格式化
     * @param string|callable $rule
     * @param string          $time
     * @return $this
     */
    public function format($rule, string $time = 'all'): self
    {
        $this->format[$time][] = $rule;
        return $this;
    }

    /**
     * 获取表单格式化
     * @param string $time
     * @return array
     */
    public function getFormat(string $time = 'add'): array
    {
        return (array)$this->format['all'] + (array)$this->format[$time];
    }

    /**
     * 自定义回调函数
     * (格式化字段，适用于跨字段读取数据)
     * e.g.
     * $form->text('URL', 'url')->custom(function ($info) {
     *     return $info->scheme . '://' . $info->url;
     * });
     *
     * @param  \Closure $func
     * @return $this
     */
    public function custom(\Closure $func)
    {
        $this->formatFunc = $func;
        return $this;
    }

    /**
     * 获取提交数据
     * @param string $time
     * @return mixed
     */
    public function getInput(string $time = 'add'): array
    {
        $data = request()->input($this->field);
        $inputs = [];
        if (method_exists($this, 'appendInput') && !$this->has) {
            $appendData = $this->appendInput($data);
            foreach ($appendData as $key => $vo) {
                $inputs[$key] = ['value' => $vo];
            }
        }

        if (method_exists($this, 'dataInput') && !$this->has) {
            $data = $this->dataInput($data);
        }
        $inputs[$this->field] = ['value' => $data, 'has' => $this->has, 'format' => $this->getFormat($time), 'verify' => $this->getVerify($time), 'pivot' => $this->pivot];

        return $inputs;
    }

    /**
     * 获取帮助信息
     * @return string|array
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * 获取顺序
     * @return null
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * 复合组件
     * @return bool
     */
    public function getComponent(): bool
    {
        return $this->component;
    }

    /**
     * 获取渲染组件
     * @return array
     */
    public function getRender(): array
    {
        if ($this->class) {
            $this->attr['class'] = implode(' ', $this->class);
        }
        return array_merge($this->render(), $this->attr);
    }


    /**
     * 获取数据值
     * @param $info
     * @return array
     */
    public function getData($info): array
    {
        $field = $this->getHas() ?: $this->getField();
        $value = Tools::parsingArrData($info, $field);

        $data = [];
        if (method_exists($this, 'appendValue')) {
            $appendValue = $this->appendValue($info);
            foreach ($appendValue as $key => $vo) {
                $data[$key] = $vo;
            }
        }
        if (method_exists($this, 'dataValue')) {
            $value = $this->dataValue($value, $info);
        } else {
            $value = $this->getValue($value);
        }

        // 自定义回调函数(格式化字段)
        if ($this->formatFunc && is_callable($this->formatFunc)) {
            $value = call_user_func($this->formatFunc, $info);
        }

        $data[$this->getField()] = $value;

        return $data;
    }


}

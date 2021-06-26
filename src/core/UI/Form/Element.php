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
    protected string $help = '';
    protected string $helpLine = '';
    protected string $prompt = '';
    protected array $class = [];
    protected array $attr = [];
    protected array $style = [];
    protected array $verify = [];
    protected array $verifyMsg = [];
    protected array $format = [];
    protected bool $layout = true;
    protected bool $label = true;
    protected bool $component = false;
    protected bool $must = false;
    protected array $group = [];
    protected ?int $sort = null;
    protected $value;
    protected $default;


    /**
     * 获取布局状态
     * @return bool
     */
    public function getLayout(): bool
    {
        return $this->layout;
    }

    /**
     * 设置布局
     * @param $layout
     * @return $this
     */
    public function layout($layout): self
    {
        $this->layout = $layout;
        return $this;
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
     * @param $value
     * @param bool $json
     * @return array|false|string[]|null
     */
    public function getValueArray($value, bool $json = false)
    {
        $value = $this->getValue($value);
        if ($value instanceof \Illuminate\Database\Eloquent\Collection && $value->count()) {
            $values = $value->pluck($value->first()->getKeyName())->toArray();
        } elseif (is_array($value)) {
            $values = $value;
        } elseif ($value !== null) {
            $values = $json ? json_decode($value, true) : explode(',', $value);
        } else {
            $values = $json ? [] : null;
        }
        return is_array($values) ? array_filter($values) : $values;
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
     * @param string $value
     * @param bool $line
     * @return $this
     */
    public function help(string $value, bool $line = false): self
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
     * @param string $name
     * @param string|array $value
     * @return $this
     */
    public function attr(string $name, $value): self
    {
        $this->attr[$name] = $value;
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
     * 设置提示
     * @param $name
     * @return $this
     */
    public function placeholder($name): self
    {
        $this->attr['placeholder'] = $name;
        return $this;
    }

    /**
     * 设置样式
     * @param $name
     * @param $value
     * @return $this
     */
    public function style(string $name, string $value): self
    {
        $this->style[$name] = $value;
        return $this;
    }

    /**
     * 转换元素属性
     * @return string
     */
    public function toElement(): string
    {
        return implode(' ', [$this->toClass(), $this->toAttr(), $this->toStyle()]);
    }

    /**
     * 转换class
     * @return string
     */
    public function toClass(): string
    {
        return Tools::toClass($this->class);
    }

    /**
     * 转换属性
     * @return string
     */
    public function toAttr(): string
    {
        return Tools::toAttr($this->attr);
    }

    /**
     * 转换样式
     * @return string
     */
    public function toStyle(): string
    {
        return Tools::toStyle($this->style);
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
     * @return string
     */
    public function getHelpLine(): string
    {
        return $this->helpLine;
    }

    /**
     * 设置字段验证
     * @param  $rule
     * @param array $msg
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
     *
     * 设置表单格式化
     * @param string|callable $rule
     * @param string $time
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
     * 获取提交数据
     * @param string $time
     * @return mixed
     */
    public function getInput(string $time = 'add'): array
    {
        $data = request()->input($this->field);
        if (method_exists($this, 'input')) {
            $data = $this->input($data);
        }
        if (method_exists($this, 'getInputData') && !$this->has) {
            $data = $this->getInputData($data);
        }
        return ['value' => $data, 'has' => $this->has, 'format' => $this->getFormat($time), 'verify' => $this->getVerify($time)];
    }

    /**
     * 获取帮助信息
     * @return string
     */
    public function getHelp(): string
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
}

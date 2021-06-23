<?php

namespace Duxravel\Core\Util;

/**
 * 模板工具
 */
class Blade
{

    /**
     * 默认标签提取
     * @param string $label
     * @return string[]
     */
    public static function label(string $label): array
    {
        $label = trim(trim($label), '\[\]');
        $data = explode(',', $label);
        $maps = [];
        array_map(static function ($item) use (&$maps) {
            $tmp = explode('=>', $item, 2);
            $key = trim($tmp[0]);
            $value = trim($tmp[1]);
            $key = trim($key, "\'\"");
            $maps[$key] = $value;
        }, $data);
        return [
            'item' => (string)$maps['item'] ?: '$item',
            'key' => (string)$maps['key'] ?: '$key',
            'assign' => (string)$maps['assign'] ? '$' . trim($maps['assign'], "\'\"") : '',
        ];
    }

    /**
     * 普通标签生成
     * @param string $name
     * @param string $class
     * @param string $method
     * @param null $template
     */
    public static function make(string $name, string $class, string $method, $template = null): void
    {
        \Illuminate\Support\Facades\Blade::directive($name, function ($label) use ($class, $method, $template) {
            $params = \Duxravel\Core\Util\Blade::label($label);
            $next = is_callable($template) ? $template($params) : $template;
            if (!$params['assign']) {
                return <<<DATA
                <?php
                    \$data = $class::$method($label);
                    $next
                    echo \$data;
                ?>
                DATA;
            } else {
                return <<<DATA
                <?php
                    {$params['assign']} = $class::$method($label);
                    $next
                ?>
                DATA;
            }
        });
    }

    /**
     * 循环标签生成
     * @param string $name
     * @param string $class
     * @param string $method
     * @param null $callback
     */
    public static function loopMake(string $name, string $class, string $method, $callback = null): void
    {

        \Illuminate\Support\Facades\Blade::directive($name, function ($label) use ($class, $method, $callback) {
            $params = \Duxravel\Core\Util\Blade::label($label);
            $next = is_callable($callback) ? $callback($params) : $callback;
            if (!$params['assign']) {
                return <<<DATA
                <?php
                    \$data = $class::$method($label);
                    $next
                    foreach(\$data as  {$params['key']} => {$params['item']}):
                ?>
                DATA;
            }
            return <<<DATA
                <?php
                    {$params['assign']} = $class::$method($label);
                    $next
                ?>
                DATA;
        });

        \Illuminate\Support\Facades\Blade::directive('end' . $name, function () {
            return '<?php endforeach; ?>';
        });
    }

}


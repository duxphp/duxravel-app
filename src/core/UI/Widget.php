<?php

namespace Duxravel\Core\UI;

/**
 * 通用部件
 * Class Composite
 * @package Duxravel\Core\UI
 * @method static \Duxravel\Core\UI\Widget\Alert alert(string $content, string $title = null, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Badge badge($content, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Images images($list, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Form form($data, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Icon icon($content, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Link link($data, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Menu menu(string $name, string $type = 'default', callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Table table($data, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Text text($content, callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\Row row(callable $callback = NULL)
 * @method static \Duxravel\Core\UI\Widget\StatsCard statsCard(callable $callback = NULL)
 */
class Widget
{

    private static $extend;

    public static function __callStatic($method, $arguments)
    {
        $class = '\\Modules\\Common\UI\\Widget\\' . ucfirst($method);
        if (!class_exists($class)) {
            if (!self::$extend[$method]) {
                throw new \RuntimeException('There is no widget method "' . $method . '"');
            } else {
                $class = self::$extend[$method];
            }
        }
        $object = new $class(...$arguments);
        return $object->next()->render();
    }
}

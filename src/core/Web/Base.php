<?php

namespace Duxravel\Core\Web;

use App\Http\Controllers\Controller;
use Duxravel\Core\Util\View;

class Base extends Controller
{

    protected array $assign = [];

    protected array $meta = [
        'title' => '',
        'keyword' => '',
        'description' => ''
    ];

    /**
     * 媒体头
     * @param $title
     * @param $keyword
     * @param $description
     */
    public function meta($title, $keyword, $description): void
    {
        $this->meta['title'] = $title;
        $this->meta['keyword'] = $keyword;
        $this->meta['description'] = $description;
    }

    /**
     * 模板赋值
     * @param $name
     * @param $value
     */
    public function assign($name, $value): void
    {
        $this->assign[$name] = $value;
    }

    /**
     * 视图输出
     * @param string $tpl
     * @param string $route
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view(string $tpl = '')
    {
        $this->meta = [
            'title' => $this->meta['title'] ? $this->meta['title'] . ' - ' . config('theme.title') : config('theme.title'),
            'keyword' => $this->meta['keyword'] ?: config('theme.keyword'),
            'description' => $this->meta['description'] ?: config('theme.description'),
        ];
        $this->assign('meta', (object) $this->meta);
        return (new View($tpl, $this->assign))->render('web');
    }

}

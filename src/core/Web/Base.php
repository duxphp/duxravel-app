<?php

namespace Duxravel\Core\Web;

use App\Http\Controllers\Controller;
use Duxravel\Core\Util\View;

class Base extends Controller
{

    protected array $assign = [];

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
        return (new View($tpl, $this->assign))->render('web');
    }

}

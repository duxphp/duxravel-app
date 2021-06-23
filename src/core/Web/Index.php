<?php

namespace Duxravel\Core\Web;

use Duxravel\Core\Web\Base;

class Index extends Base
{

    public function index()
    {
        return $this->view('index');
    }
}

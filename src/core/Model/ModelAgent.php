<?php

namespace Duxravel\Core\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ModelAgent
 * @package Duxravel\Core\Model
 */
class ModelAgent
{
    public $model;

    public function __construct(Eloquent $model)
    {
        $this->model = $model;
    }

    public function eloquent()
    {
        return $this->model;
    }

    public function __call($method, $arguments)
    {
        $this->model = $this->model->$method(...$arguments);
        return $this;
    }
}

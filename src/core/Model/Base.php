<?php

namespace Duxravel\Core\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\Builder;

/**
 * Class Base
 * @package Duxravel\Core\Model
 */
class Base extends Eloquent
{

    use Expend;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

}

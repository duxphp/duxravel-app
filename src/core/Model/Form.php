<?php

namespace Duxravel\Core\Model;

/**
 * Class Form
 * @package Modules\System\Model
 */
class Form extends \Duxravel\Core\Model\Base
{

    protected $table = 'form';

    protected $primaryKey = 'form_id';

    protected $casts = [
        'data' => 'array',
    ];

}

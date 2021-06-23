<?php

namespace Duxravel\Core\Model;

/**
 * Class Jobs
 * @package Duxravel\Core\Model
 */
class Jobs extends \Duxravel\Core\Model\Base
{

    protected $table = 'jobs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $casts = [
        'payload' => 'array',
    ];

}

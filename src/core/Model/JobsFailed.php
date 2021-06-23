<?php

namespace Duxravel\Core\Model;

/**
 * Class JobsFailed
 * @package Duxravel\Core\Model
 */
class JobsFailed extends \Duxravel\Core\Model\Base
{

    protected $table = 'jobs_failed';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $casts = [
        'payload' => 'array',
    ];

}

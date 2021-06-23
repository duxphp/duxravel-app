<?php

namespace Duxravel\Core\Model;

/**
 * Class VisitorOperate
 * @package Duxravel\Core\Model
 */
class VisitorOperate extends \Duxravel\Core\Model\Base
{

    protected $table = 'visitor_operate';

    protected $primaryKey = 'operate_id';

    protected $guarded = [];

    protected $casts = [
        'params' => 'array',
    ];

}

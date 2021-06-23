<?php

namespace Duxravel\Core\Model;

/**
 * Class FormData
 * @package Modules\System\Model
 */
class FormData extends \Duxravel\Core\Model\Base
{

    protected $table = 'form_data';

    protected $primaryKey = 'data_id';

    protected $casts = [
        'data' => 'array',
    ];

}

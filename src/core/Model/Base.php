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
    public const CREATED_AT = 'create_time';
    public const UPDATED_AT = 'update_time';
    public const DELETED_AT = 'delete_time';

    protected $dateFormat = 'U';

    protected $dates = [
        'create_time',
        'update_time',
        'delete_time',
    ];

    use Expend;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

}

<?php

namespace Duxravel\Core\Model;

/**
 * Class Role
 * @package Duxravel\Core\Model
 */
class RoleHas extends \Duxravel\Core\Model\Base
{

    protected $table = 'role_has';

    protected $primaryKey = 'role_id';

    public $timestamps = false;
}

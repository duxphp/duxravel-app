<?php

namespace Duxravel\Core\Model;

/**
 * Class Role
 * @package Duxravel\Core\Model
 */
class Role extends \Duxravel\Core\Model\Base
{

    protected $table = 'role';

    protected $primaryKey = 'role_id';

    protected $casts = [
        'purview' => 'array',
    ];

    protected $fillable = [];
    protected $guarded = [];

    public static function create(array $attributes = [])
    {
        $attributes['guard'] = $attributes['guard'] ?? 'admin';
        return static::query()->create($attributes);
    }
}

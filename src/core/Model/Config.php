<?php

namespace Duxravel\Core\Model;

use Illuminate\Support\Facades\DB;

/**
 * Class Config
 * @package Duxravel\Core\Model
 */
class Config extends \Duxravel\Core\Model\Base
{

    protected $table = 'config';

    protected $primaryKey = 'config_id';

    public $timestamps = false;

    /**
     * 不能被分配的模型
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public static function getConfig($type, $id, $key = null)
    {
        $info = self::where('has_type', $type)->where('has_id', $id)->first();
        return $key ? $info->data[$key] : collect($info->data ?: []);
    }

    public static function setConfig($type, $id, $data)
    {
        $data = is_object($data) ? $data->toArray() : ($data ?: []);
        $info = self::where('has_type', $type)->where('has_id', $id)->first();
        $data = array_merge((array)$info->data, $data);
        return self::updateOrCreate(
            ['has_type' => $type, 'has_id' => $id],
            ['data' => $data]
        );
    }

    public static function setValue($type, $id, $key, $value = null)
    {
        $info = self::where('has_type', $type)->where('has_id', $id)->first();
        $data = $info->data;
        $data[$key] = $value;
        return self::updateOrCreate(
            ['has_type' => $type, 'has_id' => $id],
            ['data' => $data]
        );
    }

}

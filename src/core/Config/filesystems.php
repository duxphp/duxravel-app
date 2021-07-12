<?php

return [

    'default' => env('FILESYSTEM_DRIVER', 'upload'),

    'cloud' => env('FILESYSTEM_CLOUD', 'qiniu'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => env('APP_URL'),
        ],

        'qiniu' => [
            'driver'     => 'qiniu',
            'access_key' => env('QINIU_AK'),
            'secret_key' => env('QINIU_SK'),
            'bucket'     => env('QINIU_BUCKET'),
            'domain'     => env('QINIU_HOST'),
        ],

        'cos' => [
            'driver' => 'cos',
            'app_id'     => env('COS_APP_ID'),
            'secret_id'  => env('COS_SECRET_ID'),
            'secret_key' => env('COS_SECRET_KEY'),
            'region'     => env('COS_REGION', 'ap-guangzhou'),
            'bucket'     => env('COS_BUCKET'),  // 不带数字 app_id 后缀
            'cdn'        => env('COS_CDN'),
            'signed_url' => false,
            'prefix' => env('COS_PATH_PREFIX'), // 全局路径前缀
            'guzzle' => [
                'timeout' => env('COS_TIMEOUT', 60),
                'connect_timeout' => env('COS_CONNECT_TIMEOUT', 60),
            ],
        ],

    ],

];

<?php
return [
    'paths' => [
        base_path(),
        resource_path('views'),
        dirname(dirname(__DIR__)) . '/resources/views'
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views')),
    ),

];

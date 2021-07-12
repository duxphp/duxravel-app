<?php

return [

    'title' => env('THEME_TITLE', 'DuxRavel CMS'),
    'keyword' => env('THEME_KEYWORD', 'DuxRavel,DuxPHP,Laravel'),
    'description' => env('THEME_DESCRIPTION', ''),

    'default' => env(
        'THEME_DEFAULT',
        'default'
    ),

    'mobile' => env(
        'THEME_MOBILE',
        'default'
    )
];

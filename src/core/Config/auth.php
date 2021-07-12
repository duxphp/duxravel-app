<?php

return [

    'defaults' => [
        'guard' => '',
        'passwords' => '',
    ],

    'guards' => [
        /*'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],*/
        /*'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],*/
    ],


    'providers' => [
        /*'admins' => [
            'driver' => 'eloquent',
            'model' => Modules\System\Model\SystemUser::class,
        ],*/
        /*'users' => [
            'driver' => 'eloquent',
            'model' => Modules\Member\Model\MemberUser::class,
        ]*/
    ],


    'passwords' => [
        /*'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],*/
    ],


    'password_timeout' => 10800,

];

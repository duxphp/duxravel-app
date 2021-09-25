<?php

return [

    'defaults' => [
        'guard' => 'admin',
    ],

    'guards' => [
        /*'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],*/
    ],


    'providers' => [
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

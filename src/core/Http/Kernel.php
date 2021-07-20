<?php

namespace Duxravel\Core\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel extends \Illuminate\Foundation\Http\Kernel
{

    /**
     * 全局中间层
     * @var array
     */
    protected $middleware = [
        \Duxravel\Core\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,

        \Duxravel\Core\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        \Duxravel\Core\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        \Duxravel\Core\Middleware\CheckInstall::class,
        \Duxravel\Core\Middleware\VisitorBefore::class,
        \Duxravel\Core\Middleware\VisitorAfter::class
    ];
    /**
     * 路由分组中间层
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Duxravel\Core\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            \Duxravel\Core\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            \Duxravel\Core\Middleware\CrossHttp::class,

            \Duxravel\Core\Middleware\Web::class
        ],
        'api' => [
            \Duxravel\Core\Middleware\Header::class,
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Duxravel\Core\Middleware\Api::class,
        ],

        'auth.manage' => [
            'web',
            \Duxravel\Core\Middleware\Manage::class,
        ],

        'auth.manage.register' => [
            'web',
            \Duxravel\Core\Middleware\ManageRegister::class,
        ]
    ];

    /**
     * 路由独立中间层
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}

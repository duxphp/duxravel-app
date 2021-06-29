<?php

namespace Duxravel\Core\Middleware;

use Closure;

class Web
{

    public function handle($request, Closure $next)
    {
        $agent = new \Jenssegers\Agent\Agent();
        $theme = config('theme.default');
        $mobileTheme = config('theme.mobile');
        if ($mobileTheme && ($agent->isMobile() || $agent->isTablet())) {
            $theme = $mobileTheme;
        }
        $finder = app('view')->getFinder();
        $finder->prependLocation(base_path('public/themes/' . $theme));
        $finder->prependLocation(base_path('modules'));
        return $next($request);
    }

}
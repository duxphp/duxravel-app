<?php

namespace Duxravel\Core\Foundation;

use Illuminate\Foundation\Application;

class ComposerScripts
{

    protected static function postAutoloadDump()
    {
        self::clear();
    }

    protected static function clear()
    {
        $laravel = new Application(getcwd());
        if (is_file($servicesPath = $laravel->bootstrapPath('cache/duxravel.php'))) {
            @unlink($servicesPath);
        }
    }
}

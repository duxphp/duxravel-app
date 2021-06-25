<?php

namespace Duxravel\Core\Foundation;

use Composer\Script\Event;
use Illuminate\Foundation\Application;

class ComposerScripts
{

    protected static function postAutoloadDump(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';
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

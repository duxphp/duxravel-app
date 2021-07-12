<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Str;

class Kernel extends ConsoleKernel
{

    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
    }

    /**
     * 定义命令
     * @var array
     */
    protected $commands = [
        \Duxravel\Core\Console\AppBuild::class,
        \Duxravel\Core\Console\App::class,
        \Duxravel\Core\Console\AppAdmin::class,
        \Duxravel\Core\Console\AppModel::class,
        \Duxravel\Core\Console\Install::class,
        \Duxravel\Core\Console\Uninstall::class,
        \Duxravel\Core\Console\InstallStatic::class,
        \Duxravel\Core\Console\UninstallStatic::class,
        \Duxravel\Core\Console\Operate::class,
        \Duxravel\Core\Console\Visitor::class,
    ];

    /**
     * 调度命令
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每天执行访客清理
        $schedule->command('visitor:clear')->daily();
        // 每周三清理操作日志
        $schedule->command('operate:clear')->weeklyOn(3);
    }

    /**
     * 注册命令
     * @return void
     */
    protected function commands()
    {
        $list = \Duxravel\Core\Util\Cache::globList(base_path('modules') . '/*/Console/*.php');
        foreach ($list as $file) {
                $this->commands[] = file_class($file);
        }

        //$this->load(__DIR__ . '/Commands');
    }
}

<?php

namespace Duxravel\Core\Console;

class AppBuild extends \Duxravel\Core\Console\Common\Stub
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '编译应用结构';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Duxravel\Composer\Scripts::clearCompiled();
        app(\Duxravel\Core\Util\Build::class)->build();
        $this->info('编译结构成功');
    }

}

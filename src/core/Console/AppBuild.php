<?php

namespace Duxravel\Core\Console;

class AppBuild extends \Duxravel\Core\Console\Common\Stub
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bulid';

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

        app(\Duxravel\Core\Util\Cache::class)->build();

        $this->info('编译结构成功');
    }

}

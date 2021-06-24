<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class UninstallStatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:uninstall-static {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '静态资源卸载';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $appDir = strtolower($name);
        $dir = base_path('public/static/' . $appDir);
        \File::deleteDirectory($dir);

        $this->info('卸载静态资源成功');
    }
}

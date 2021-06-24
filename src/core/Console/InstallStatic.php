<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class InstallStatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install-static {name} {--path=} {--update=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '静态资源安装';

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
        $path = $this->option('path');

        $appDir = strtolower($name);
        $dir = base_path('public/static/' . $appDir);
        \File::deleteDirectories($dir);
        \File::copyDirectory($path . '/dist', $dir);

        $this->info('安装静态资源成功');
    }
}

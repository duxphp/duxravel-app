<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class Uninstall extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:uninstall {name}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = '扩展应用卸载';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (strpos($name, '/') === false) {
            // 本地应用
            $appDir = ucfirst($name);
            $dir = base_path('modules/' . $appDir);
            $database = $dir . '/Database';
            $migrations = $database . '/Migrations';
            $publish = 'duxapp-' . strtolower($name);
        } else {
            // 包应用
            $dir = base_path('vendor/' . trim($name, '/'));
            $database = $dir . '/database';
            $migrations = $database . '/migrations';
            $dir = explode('/', $name);
            $publish = str_replace('/', '-', end($dir));
        }

        // 数据表卸载
        if (is_dir($migrations)) {
            $path = $migrations . '/*.php';
            $fileList = glob($path);
            foreach ($fileList as $file) {
                $file = str_replace(base_path(), '', $file);
                $this->callSilent('migrate:reset', [
                    '--path' => $file,
                    '--force' => true,
                ]);
            }
        }

        $this->callSilent('app:build');
        $this->info('Application uninstalled successfully');
    }
}

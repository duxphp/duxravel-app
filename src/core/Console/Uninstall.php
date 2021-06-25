<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class Uninstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:uninstall {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '扩展应用卸载';

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
        $appDir = ucfirst($name);
        $dir = base_path('/modules/' . $appDir);
        $database = $dir . '/Database';

        // 数据表卸载
        if (is_dir($database . '/Migrations')) {
            $path = $database . '/Migrations/*.php';
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

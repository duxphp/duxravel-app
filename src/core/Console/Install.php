<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:install {name} {--update=}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = '扩展应用安装';

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
        $update = $this->argument('update');

        if (strpos($name, '/') === false) {
            // 本地应用
            $appDir = ucfirst($name);
            $dir = base_path('modules/' . $appDir);
            $database = $dir . '/Database';
            $migrations = $database . '/Migrations';
            $seeders = $database . '/Seeders/DatabaseSeeder.php';
            $publish = 'duxapp-' . strtolower($name);
        } else {
            // 包应用
            $dir = base_path('vendor/' . trim($name, '/'));
            $database = $dir . '/database';
            $migrations = $database . '/migrations';
            $seeders = $database . '/seeders/DatabaseSeeder.php';
            $publish = $name;
        }


        // 数据表安装
        if (is_dir($migrations)) {
            $path = $migrations . '/*.php';
            $fileList = glob($path);
            foreach ($fileList as $file) {
                $file = str_replace(base_path(), '', $file);
                $this->callSilent('migrate', [
                    '--path' => $file,
                    '--force' => $update || true,
                ]);
            }
        }

        // 数据安装
        if (is_file($seeders) && !$update) {
            $class = file_class($seeders);
            if($class) {
                $this->callSilent('db:seed', [
                    '--force' => true,
                    '--class' => $class,
                ]);
            }
        }

        // 发布配置
        $this->callSilent('vendor:publish', [
            '--tag' => $publish,
            '--force' => true,
        ]);

        $this->callSilent('app:build');
        $this->info('Installation and application successful');
    }
}

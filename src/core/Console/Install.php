<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {name} {--update=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '扩展应用安装';

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
        $dir = base_path('modules/' . $appDir);
        $database = $dir . '/Database';
        // 数据表安装
        if (is_dir($database . '/Migrations')) {
            $path = $database . '/Migrations/*.php';
            $fileList = glob($path);
            foreach ($fileList as $file) {
                $file = str_replace(base_path(), '', $file);
                $this->callSilent('migrate', [
                    '--path' => $file,
                    '--force' => true,
                ]);
            }
        }

        //数据安装
        if (is_dir($database . '/Seeders')) {
            $path = $database . '/Seeders/*.php';
            $fileList = glob($path);
            if (!empty($fileList)) {
                $data = [];
                foreach ($fileList as $file) {
                    $data[] = file_class($file);
                }
                foreach ($data as $vo) {
                    $this->callSilent('db:seed', [
                        '--force' => true,
                        '--class' => $vo,
                    ]);
                }
            }
        }
        $this->callSilent('app:bulid');
        $this->info('Installation and application successful');
    }
}

<?php

namespace Duxravel\Core\Console;

class App extends \Duxravel\Core\Console\Common\Stub
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make 
                                {name : 应用名}
                                {--title= : 应用名称}
                                {--desc= : 应用描述}
                                {--auth= : 应用作者}
                                ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建应用结构';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $title = $this->option('title') ?: '应用名称';
        $desc = $this->option('desc') ?: '应用描述';
        $auth = $this->option('auth') ?: '应用作者';
        $name = $this->argument('name');
        $appDir = ucfirst($name);
        if (is_dir(base_path('/modules/' . $appDir))) {
            $this->error('应用已存在，请更换应用名!');
            exit;
        }
        $id = (new \Godruoyi\Snowflake\Snowflake)->id();
        // 创建应用结构
        $this->generatorDir($appDir);
        $this->generatorDir($appDir . '/' . 'Admin');
        $this->generatorDir($appDir . '/' . 'Api');
        $this->generatorDir($appDir . '/' . 'Model');
        $this->generatorDir($appDir . '/' . 'Config');
        $this->generatorDir($appDir . '/' . 'Route');
        $this->generatorDir($appDir . '/' . 'Service');
        $this->generatorDir($appDir . '/' . 'View');
        $this->generatorDir($appDir . '/' . 'View/Admin');
        // 创建初始文件
        $this->generatorFile($appDir . '/' . 'Config/Config.php', __DIR__ . '/Tpl/App/Config.stub', [
            'id' => $id,
            'title' => $title,
            'system' => 0,
            'auth' => $auth,
            'desc' => $desc,
            'icon' => '',
        ]);
        $this->generatorFile($appDir . '/' . 'Service/Menu.php', __DIR__ . '/Tpl/App/Menu.stub', [
            'appDir' => $appDir,
            'name' => $name,
            'menu' => $title,
            'icon' => '',
        ]);
        $this->generatorFile($appDir . '/' . 'Route/AuthAdmin.php', __DIR__ . '/Tpl/App/AuthAdmin.stub', [
            'title' => $title,
            'name' => $name,
        ]);
        $this->callSilent('app:build');
        $this->info('创建应用成功');
    }

}

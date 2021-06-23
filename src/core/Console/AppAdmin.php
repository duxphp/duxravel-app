<?php

namespace Duxravel\Core\Console;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AppAdmin extends \Duxravel\Core\Console\Common\Stub
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建 admin 控制器';

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
        $app = ucfirst($name);
        if (!is_dir(base_path('/modules/'.$app))) {
            $this->error('应用不存在，请检查!');
            exit;
        }
        $fun = lcfirst($this->getAppName('请输入类名'));
        $class = ucfirst($fun);
        $title = $this->ask('请输入功能名称');
        $modelClass = '\Duxravel\Core\Model\Base';

        // 创建控制器
        $this->generatorFile($app."/Admin/{$class}.php", __DIR__.'/Tpl/AppAdmin/Admin.stub', [
            'app' => $app,
            'title' => $title,
            'class' => $class,
            'modelClass' => $modelClass,
        ]);

        // 创建路由
        $routeFile = base_path('/modules/'.$app.'/Route/AuthAdmin.php');
        $routeContent = file_get_contents($routeFile);
        $routeContent = str_replace('    // Generate Route Make',
            <<<PHP
                    Route::group([
                        'auth_group' => '$title'
                    ], function () {
                        Route::manage(\\Modules\\$app\Admin\\$class::class)->make();
                    });
                    // Generate Route Make
                PHP
            , $routeContent);
        file_put_contents($routeFile, $routeContent);

        // 创建菜单
        $menuFile = base_path('/modules/'.$app.'/Service/Menu.php');
        $menuContent = file_get_contents($menuFile);
        $menuContent = str_replace('                            // Generate Menu Make',
            <<<EOL
                        [
                            'name'  => '$title',
                            'url'   => 'admin.$name.$fun',
                        ],
                        // Generate Menu Make
                    EOL, $menuContent);
        file_put_contents($menuFile, $menuContent);

        $this->info('创建模型成功');
    }

}

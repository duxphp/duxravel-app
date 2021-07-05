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
    protected $signature = 'app:make-admin {name} {--title= : 功能名称}';

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
        $title = $this->option('title');
        $app = ucfirst($name);
        if (!is_dir(base_path('/modules/' . $app))) {
            $this->error('应用不存在，请检查!');
            exit;
        }
        $fun = lcfirst($this->getAppName('请输入类名'));
        $class = ucfirst($fun);
        if (!$title) {
            $title = $this->ask('请输入功能名称');
        }
        $modelClass = '\Duxravel\Core\Model\Base';

        // 创建控制器
        $this->generatorFile($app . "/Admin/{$class}.php", __DIR__ . '/Tpl/AppAdmin/Admin.stub', [
            'app' => $app,
            'title' => $title,
            'class' => $class,
            'modelClass' => $modelClass,
        ]);

        // 创建路由
        $routeFile = base_path('/modules/' . $app . '/Route/AuthAdmin.php');

        $this->appendFile($routeFile, <<<EOL
                                    Route::group([
                                        'auth_group' => '$title'
                                    ], function () {
                                        Route::manage(\\Modules\\$app\Admin\\$class::class)->make();
                                    });
                                    EOL,
            '// Generate Route Make');

        // 创建菜单
        $menuFile = base_path('/modules/' . $app . '/Service/Menu.php');

        $this->appendFile($menuFile, <<<EOL
                                    [
                                        'name'  => '$title',
                                        'url'   => 'admin.$name.$fun',
                                    ],
                                    EOL,
            '// Generate Menu Make');

        $this->info('创建模型成功');
    }

    public function appendFile($file, $content = '', $mark = '')
    {
        $file = base_path('/modules/' . $file);
        $data = [];
        $contentData = explode("\n", $content);
        foreach (file($file) as $line) {
            if (strpos($file, $mark) !== false) {
                $place = substr($line, 0, strrpos($line, $mark));
                foreach ($contentData as $content) {
                    $data[] = $place . $content;
                }
            }
            $data[] = $line;
        }
        file_put_contents($file, implode("\n", $data));
    }

}

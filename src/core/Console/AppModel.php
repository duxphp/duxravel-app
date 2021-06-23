<?php

namespace Duxravel\Core\Console;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AppModel extends \Duxravel\Core\Console\Common\Stub
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建数据模型';

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
        if (!is_dir(base_path('/modules/' . $app))) {
            $this->error('应用不存在，请检查!');
            exit;
        }
        $table = $this->ask('请输入表名(英文+下划线)');
        $key = $this->ask('请输入主键');
        $tmpArr = explode('_', $table);
        $modelName = implode('', array_map(function ($vo) {
            return ucfirst($vo);
        }, $tmpArr));

        //创建模型
        Schema::create($table, function (Blueprint $table) use ($key) {
            $table->increments($key);
            $table->integer('create_time');
            $table->integer('update_time');
        });
        $this->generatorFile($app . "/Model/{$modelName}.php", __DIR__ . '/Tpl/AppModel/Model.stub', [
            'app' => $app,
            'table' => $table,
            'modelName' => $modelName,
            'key' => $key,
        ]);

        $this->info('创建模型成功');
    }
}

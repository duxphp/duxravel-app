<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class Operate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operate:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '操作定时清理';

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

        app(\Duxravel\Core\Model\VisitorOperate::class)->where('updated_at', '<=', date('Y-m-d H:i:s'))->delete();
    }
}

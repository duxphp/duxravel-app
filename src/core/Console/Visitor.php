<?php

namespace Duxravel\Core\Console;

use Illuminate\Console\Command;

class Visitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitor:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '访客定时清理';

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
        app(\Duxravel\Core\Model\VisitorLog::class)->where('update_time', '<=', time())->delete();
    }
}

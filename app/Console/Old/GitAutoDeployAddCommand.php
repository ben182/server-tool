<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use Illuminate\Console\Command;
use App\Console\Commands\Tasks\GitAutoDeployTaskManager;

class GitAutoDeployAddCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:add {--dir=} {--branch=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $sDir    = $this->stringOption('dir', 'Path (from /home/stool/)?');
        $sBranch = $this->stringOption('branch', 'Branch?');

        (new GitAutoDeployTaskManager([
            'dir'    => "/home/stool/$sDir",
            'branch' => $sBranch,
        ]))->work();
    }
}

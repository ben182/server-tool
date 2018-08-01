<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Repository;
use Illuminate\Console\Command;
use App\Console\Commands\Tasks\GitAutoDeployTaskManager;

class GitAutoDeployAddCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:add {--dir=} {--branch=} {--hardreset} {--nooutput}';

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
        $sDir = $this->stringOption('dir', 'Path (from /var/www/)?');
        $sBranch = $this->stringOption('branch', 'Branch?');
        $bReset = $this->booleanOption('hardreset', 'Hard Reset?', true);

        (new GitAutoDeployTaskManager([
            'dir' => "/var/www/$sDir",
            'branch' => $sBranch,
            'hardreset' => $bReset
        ]))->work();
    }
}

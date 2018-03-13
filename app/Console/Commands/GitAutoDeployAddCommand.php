<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Http\Controllers\RouteController;
use Illuminate\Console\Command;

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
        $iReset = (int) $this->booleanOption('hardreset', 'Hard Reset?', 1);

        if (!file_exists("/var/www/$sDir")) {
            $this->abort("/var/www/$sDir does not exist");
        }

        $aRoute = RouteController::add([
            'dir' => $sDir,
            'branch' => $sBranch,
            'reset' => $iReset,
        ]);

        $this->addToReturn('Add this route to a new github repo webhook');
        $this->addToReturn(route('api.gad.deploy', [
            'id' => $aRoute['id']
        ]));
        $this->addToReturn('Put this as a secret');
        $this->addToReturn($aRoute['secret']);

        echo $this->getReturn();
    }
}

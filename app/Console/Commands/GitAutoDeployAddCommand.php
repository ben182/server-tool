<?php

namespace App\Console\Commands;

use App\Http\Controllers\RouteController;
use Illuminate\Console\Command;

class GitAutoDeployAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:add {--dir=} {--branch=} {--hardreset=}';

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
        $sDir = $this->option('dir') ?? $this->ask('Path (from /var/www/)?');
        $sBranch = $this->option('branch') ?? $this->ask('Branch?');
        $iReset = (int) ($this->option('hardreset') ?? $this->confirm('Hard Reset?', 1));

        if (!file_exists("/var/www/$sDir")) {
            $this->abort("/var/www/$sDir does not exist");
        }

        $aRoute = RouteController::add([
            'dir' => $sDir,
            'branch' => $sBranch,
            'reset' => $iReset,
        ]);

        $this->addToReturn('Route');
        $this->addToReturn(route('api.gad.deploy', [
            'id' => $aRoute['id']
        ]));
        $this->addToReturn('Secret');
        $this->addToReturn($aRoute['secret']);
    }
}

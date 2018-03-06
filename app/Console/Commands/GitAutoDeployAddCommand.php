<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RouteController;

class GitAutoDeployAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:add {--dir=} {--branch=} {--hardreset}';

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
        $iReset = (int) ($this->option('hardreset') ??  $this->confirm('Hard Reset?', 1));

        $aRoute = RouteController::add([
            'dir' => $sDir,
            'branch' => $sBranch,
            'reset' => $iReset,
        ]);

        $this->line('Route');
        $this->line(route('api.gad.deploy', [
            'id' => $aRoute['id']
        ]));
        $this->line('Secret');
        $this->line($aRoute['secret']);
    }
}

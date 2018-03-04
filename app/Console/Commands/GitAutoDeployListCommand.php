<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RouteController;

class GitAutoDeployListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:list';

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
        $aRoutes = RouteController::getAll();

        foreach ($aRoutes as $sKey => $aRoute) {
            $this->line($sKey . ':');

            $this->line(' - Url: ' . route('api.gad.deploy', [
                'id' => $aRoute['id']
            ]));
            foreach ($aRoute as $sKey => $sItem) {
                $this->line(' - ' . $sKey . ': ' . $sItem);
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\CreateDeamonTaskManager;
use App\Console\ModCommand;
use App\Setting;

class InstallationFinishCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:finish';

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
        parent::handle();

        (new CreateDeamonTaskManager([
            'name'    => 'stool-deploy',
            'command' => 'stool queue:listen --timeout=600 --sleep=3 --tries=1',
        ]))->work();

        $sEmail = $this->ask('Administrator email?');

        Setting::create([
            'key'   => 'admin_email',
            'value' => $sEmail,
        ]);

        $bAddSwap = $this->confirm('Add Swap Space?');
        if ($bAddSwap) {
            $iSwap = (int) $this->ask('How much (in GB)?');

            echo resolve('ShellTask')->exec('bash ' . scripts_path('partials') . '/swap.sh ' . $iSwap . 'G')->getLastOutput();
        }
    }
}

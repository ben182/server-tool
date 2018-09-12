<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\CreateDeamonTaskManager;
use App\Console\ModCommand;
use App\Setting;
use App\Services\ApiRequestService;

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

        // Deploy Job
        (new CreateDeamonTaskManager([
            'name'    => 'stool-deploy',
            'command' => 'stool queue:listen --timeout=600 --sleep=15 --tries=1',
        ]))->work();

        // Admin Email
        $sEmail = $this->ask('Administrator email?');
        Setting::create([
            'key'   => 'admin_email',
            'value' => $sEmail,
        ]);

        // Slack Deploy Notification
        $bDeployNotification = $this->confirm('Setup Slack Deployment Notification?');
        if ($bDeployNotification) {
            $this->line('Visit ' . config('services.stool.base') . '/deploy/login/slack and come back with a token');
            $sToken = $this->ask('Token?');
            $sChannel = $this->ask('Channel?');

            if ($sToken && $sChannel) {
                (new ApiRequestService())->request('verifySlack', [
                    'public_id' => $sToken,
                    'channel' => $sChannel,
                ]); // TODO: validate response

                Setting::create([
                    'key'   => 'deploy_slack_token',
                    'value' => $sToken,
                ]);
            }
        }

        // Swap
        $bAddSwap = $this->confirm('Add Swap Space?');
        if ($bAddSwap) {
            $iSwap = (int) $this->ask('How much (in GB)?');

            resolve('ShellTask')->exec('bash ' . scripts_path('partials') . '/swap.sh ' . $iSwap . 'G');
        }
    }
}

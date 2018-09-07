<?php

namespace App\Jobs;

use App\Repository;
use App\Services\ApiRequestService;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Deploy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $repository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sCommand = 'cd ' . $this->repository->dir . ' && bash deploy_stool.sh 2>&1';

        exec($sCommand, $aOutput, $iExit);

        if ($iExit != 0) {
            (new ApiRequestService())->request('sendEmail', [
                'type'       => 'DeployFailed',
                'email'      => Setting::where('key', 'admin_email')->value('value'),
                'repository' => $this->repository->dir,
                'exit'       => $iExit,
                'output'     => implode("<br>", $aOutput),
            ]);
        }
        
        echo implode("\n", $aOutput);
    }
}

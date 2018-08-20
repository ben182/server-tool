<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeployFailed;
use App\Setting;
use App\Services\ApiRequestService;

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
            throw new \Exception(serialize([
                'exit' => $iExit,
                'output' => $aOutput,
            ])); // TODO: set -e for deploy
        }
        
        echo implode("\n", $aOutput);
    }
    
    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $aData = unserialize($exception->getMessage());

        (new ApiRequestService())->request('sendEmail', [
            'type' => 'DeployFailed',
            'email' => Setting::whereKey('admin_email')->value('value'),
            'repository' => $this->repository->dir,
            'exit' => $aData['exit'],
            'output' => $aData['output'],
        ]);
    }
}

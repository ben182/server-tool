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
use Illuminate\Support\Facades\File;
use App\Services\Archive;
use Pressutto\LaravelSlack\Facades\Slack;

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
        Slack::send('Deploy started in ' . $this->repository->dir);

        $sBackupFilename = str_slug('backup_' . microtime());

        $aPath = explode('/', $this->repository->dir);
        array_pop($aPath);
        $aPath[] = $sBackupFilename;
        $sBackupPath = implode('/', $aPath);

        Archive::make($sBackupPath, $this->repository->dir);

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

            File::deleteDirectory($this->repository->dir);
            Archive::extract($sBackupPath);

            Slack::send('Deploy failed. Output comes shortly.');
        }else{

            Slack::send('Deploy finished successfully. Output comes shortly.');
        }

        File::delete($sBackupPath . '.tar.gz');

        echo implode("\n", $aOutput);
        Slack::send(implode("\n", $aOutput));
    }
}

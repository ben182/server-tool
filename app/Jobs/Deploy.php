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
use App\Services\Slack;

class Deploy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $repository;
    protected $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Repository $repository, $payload)
    {
        $this->repository = $repository;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oSlack = new Slack();
        $oSlack->send('A new deploy started in ' . $this->repository->dir . ' on ' . getIp() . ' | ' . gethostname() . ' :tada:', 'bold');
        $oSlack->send('Commit: ' . $this->payload->head_commit->message . ' | ' . $this->payload->head_commit->id . ' pushed by ' . $this->payload->head_commit->committer->name);

        $sBackupFilename = str_slug('backup_' . microtime());

        $aPath = explode('/', $this->repository->dir);
        array_pop($aPath);
        $aPath[] = $sBackupFilename;
        $sBackupPath = implode('/', $aPath);

        Archive::make($sBackupPath, $this->repository->dir);

        $sCommand = 'cd ' . $this->repository->dir . ' && bash -e deploy_stool.sh 2>&1';

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

            $oSlack->send('Deploy failed');
            $oSlack->send(implode("\n", $aOutput));
        }else{

            $oSlack->send('Deploy finished successfully. Have a beer :beer:');
        }

        File::delete($sBackupPath . '.tar.gz');

        echo implode("\n", $aOutput);
    }
}

<?php

namespace App\Console\Commands\Tasks\RedisBackup;

use App\Console\Commands\Tasks\Task;
use App\Services\BackupService;
use App\Task as TaskModel;

class RedisBackupTask extends Task
{
    public $sName = 'Backing up Redis';

    public function systemRequirements()
    {
        return 'redis';
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $sFileName = date('d-m-Y_H-i-s') . '.json';

        $this->shell->exec('redis-dump -a \'' . getRedisPassword() . '\' > ' . base_path($sFileName));

        BackupService::backup($this->oOptions->storage, 'redis', $sFileName);

        if ($this->oOptions->cronjob) {
            TaskModel::create([
                'command'   => 'redis:backup',
                'parameter' => [
                    '--storage' => $this->oOptions->storage,
                ],
                'frequency' => 'daily'
            ]);
        }
    }
}

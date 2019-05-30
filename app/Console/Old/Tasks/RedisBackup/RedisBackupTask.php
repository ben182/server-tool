<?php

namespace App\Console\Commands\Tasks\RedisBackup;

use App\Task as TaskModel;
use App\Services\BackupService;
use App\Console\Commands\Tasks\Task;

class RedisBackupTask extends Task
{
    public $name = 'Backing up Redis';

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

        BackupService::backup($this->options->storage, 'redis', $sFileName);

        if ($this->options->cronjob) {
            TaskModel::create([
                'command'   => 'redis:backup',
                'parameter' => [
                    '--storage' => $this->options->storage,
                ],
                'frequency' => 'daily',
            ]);
        }
    }
}

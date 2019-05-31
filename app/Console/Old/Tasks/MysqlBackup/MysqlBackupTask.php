<?php

namespace App\Console\Commands\Tasks\MysqlBackup;

use App\Console\Task;
use App\Task as TaskModel;
use App\Services\BackupService;

class MysqlBackupTask extends Task
{
    public $name = 'Backing up MySQL';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $sFileName = ($this->options->allDatabases ? 'alldatabases' : $this->options->database) . '_' . date('d-m-Y_H-i-s') . '.sql';

        $this->shell->exec('mysqldump ' . getMysqlCredentials() . ' ' . ($this->options->allDatabases ? '--all-databases' : $this->options->database) . ' > ' . base_path($sFileName));

        BackupService::backup($this->options->storage, 'mysql', $sFileName);

        if ($this->options->cronjob) {
            TaskModel::create([
                'command'   => 'mysql:backup',
                'parameter' => [
                    '--allDatabases' => $this->options->allDatabases,
                    '--database'     => $this->options->database,
                    '--storage'      => $this->options->storage,
                ],
                'frequency' => 'daily',
            ]);
        }
    }
}

<?php

namespace App\Console\Commands\Tasks\MysqlBackup;

use App\Console\Commands\Tasks\Task;
use App\Helper\BackupService;

class MysqlBackupTask extends Task
{
    public $sName = 'Backing up MySQL';

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
        $sFileName = ($this->oOptions->allDatabases ? 'alldatabases' : $this->oOptions->database) . '_' . date('d-m-Y_H-i-s') . '.sql';

        $this->shell->exec('mysqldump ' . getMysqlCredentials() . ' ' . ($this->oOptions->allDatabases ? '--all-databases' : $this->oOptions->database) . ' > ' . base_path($sFileName));

        BackupService::backup($this->oOptions->storage, 'mysql', $sFileName);

        if ($this->oOptions->cronjob) {
            Task::create([
                'command'   => 'mysql:backup',
                'parameter' => [
                    '--allDatabases' => $this->oOptions->allDatabases,
                    '--database'     => $this->oOptions->database,
                    '--storage'      => $this->oOptions->storage,
                ],
                'frequency' => 'daily',
            ]);
        }
    }
}

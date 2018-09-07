<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\MysqlBackupTaskManager;
use App\Console\ModCommand;

class MysqlBackup extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql:backup {--database=} {--storage=} {--allDatabases} {--cronjob}';

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

        $bAllDatabases = $this->booleanOption('allDatabases', 'Backup all databases?');

        $sAskedDbName = '';
        if (! $bAllDatabases) {
            $sAskedDbName = $this->stringOption('database', 'Database Name?');
        }

        if (isSpacesSet()) {
            $sStorage = $this->choiceOption('storage', 'Upload to local or digitalocean spaces?', [
                'local',
                'spaces',
            ]);
        } else {
            $sStorage = 'local';
        }

        $bCronjob = $this->booleanOption('cronjob', 'Set up a cronjob that runs daily?');

        (new MysqlBackupTaskManager([
            'allDatabases' => $bAllDatabases,
            'database'     => $sAskedDbName,
            'storage'      => $sStorage,
            'cronjob'      => $bCronjob,
        ]))->work();
    }
}

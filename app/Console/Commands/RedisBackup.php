<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\RedisBackupTaskManager;
use App\Console\ModCommand;

class RedisBackup extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:backup {--storage=} {--cronjob}';

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

        if (isSpacesSet()) {
            $sStorage = $this->choiceOption('storage', 'Upload to local or digitalocean spaces?', [
                'local',
                'spaces',
            ]);
        }else{
            $sStorage = 'local';
        }

        $bCronjob = $this->booleanOption('cronjob', 'Set up a cronjob that runs daily?');

        (new RedisBackupTaskManager([
            'storage' => $sStorage,
            'cronjob' => $bCronjob,
        ]))->work();
    }
}

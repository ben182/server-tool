<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\SnapshotBackupTaskManager;
use Illuminate\Console\Command;

class SnapshotBackupExecute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:backup
                            {keep : The amount of backups to keep}';

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
        $iKeep = $this->argument('keep');

        (new SnapshotBackupTaskManager([
            'keep' => $iKeep,
        ]))->work();
    }
}

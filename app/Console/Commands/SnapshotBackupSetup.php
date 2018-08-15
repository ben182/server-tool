<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\SnapshotBackupSetupTaskManager;
use App\Console\ModCommand;

class SnapshotBackupSetup extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:setup';

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

        $sToken = $this->secret('What is your DigitalOcean API Token?');
        $iKeep = (int) $this->ask('How much snapshots to keep?');

        (new SnapshotBackupSetupTaskManager([
            'doToken' => $sToken,
            'keep' => $iKeep,
        ]))->work();
    }
}

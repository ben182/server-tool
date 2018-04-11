<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SnapshotBackupSetup extends Command
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
        $sToken = $this->secret('What is your DigitalOcean API Token?');

        shell_exec('echo "DOAT=\"' . encrypt($sToken) . '\"" >> /etc/environment');

        $iKeep = (int) $this->ask('How much snapshots to keep?');

        $this->task('Installing Dependencies', function () {
            echo shell_exec('gem install do_snapshot >> /dev/null 2>&1');
            return true;
        });

        $this->task('Setting up Cronjob', function () use ($iKeep) {
            echo shell_exec('crontab -l | { cat; echo "0 0 * * * server-tools snapshot:backup ' . $iKeep . ' >> /dev/null 2>&1"; } | crontab -');
            return true;
        });
    }
}

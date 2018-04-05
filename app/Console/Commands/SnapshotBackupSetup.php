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
        if (!$this->confirm('Do you want to setup automatic snapshots of this droplet?')) {
            return false;
        }

        $sToken = $this->secret('What is your DigitalOcean API Token?');
        shell_exec("export DIGITAL_OCEAN_ACCESS_TOKEN=$sToken");

        $iKeep = (int) $this->ask('How much snapshots to keep?');

        shell_exec('gem install do_snapshot');
        echo shell_exec('crontab -l | { cat; echo "0 0 * * * /usr/local/bin/do_snapshot --only $DROPLET_ID -k ' . $iKeep . ' -c -q >> /dev/null 2>&1"; } | crontab -');
    }
}

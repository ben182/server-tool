<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SnapshotBackupExecute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:execute {keep}';

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
        $iDropletId = getenv('DROPLET_ID');
        if (!$iDropletId) {
            return false;
        }

        $sToken = decrypt(getenv('DOAT'));
        if (!$sToken) {
            return false;
        }

        shell_exec("/usr/local/bin/do_snapshot --only ' . $iDropletId . ' -k ' . $iKeep . ' -c -q --digital-ocean-access-token " . $sToken);
    }
}

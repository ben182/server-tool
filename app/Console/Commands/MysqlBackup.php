<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MysqlBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql:backup';

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
        $aParams = [];

        $bAllDatabases = $this->confirm('Backup all databases?');

        if ($bAllDatabases) {
            $aParams[] = '--all-databases';
        }

        $sAskedDbName = '';
        if (!$bAllDatabases) {
            $sAskedDbName = $this->ask('Database Name?');
        }

        $sUploadDriver = $this->choice('Upload to local or digitalocean spaces?', ['local', 'spaces']);

        $sFileName = ($bAllDatabases ? 'alldatabases' : $sAskedDbName) . '_' . date('d-m-Y_H-i-s') . '.sql';

        echo shell_exec('mysqldump ' . getMysqlCredentials() . ' ' . implode(' ', $aParams) . ($bAllDatabases ? '' : ' ' . $sAskedDbName) . ' > ' . base_path($sFileName));

        Storage::disk($sUploadDriver)->put('backups/mysql/' . $sFileName, file_get_contents(base_path($sFileName)));

        unlink(base_path($sFileName));
    }
}

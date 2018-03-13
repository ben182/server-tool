<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Task;
use Illuminate\Support\Facades\Storage;

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
        $aParams = [];

        $bAllDatabases = $this->booleanOption('allDatabases', 'Backup all databases?');

        if ($bAllDatabases) {
            $aParams[] = '--all-databases';
        }

        $sAskedDbName = '';
        if (!$bAllDatabases) {
            $sAskedDbName = $this->stringOption('database', 'Database Name?');
        }

        $sUploadDriver = $this->choiceOption('storage', 'Upload to local or digitalocean spaces?', ['local', 'spaces']);

        $bCronjob = $this->booleanOption('cronjob', 'Set up a cronjob that runs daily?');

        $sFileName = ($bAllDatabases ? 'alldatabases' : $sAskedDbName) . '_' . date('d-m-Y_H-i-s') . '.sql';

        //shell_exec('mysqldump ' . getMysqlCredentials() . ' ' . implode(' ', $aParams) . ($bAllDatabases ? '' : ' ' . $sAskedDbName) . ' > ' . base_path($sFileName));

        //Storage::disk($sUploadDriver)->put(buildBackupPath('mysql', $sFileName), file_get_contents(base_path($sFileName)));

        //unlink(base_path($sFileName));

        dd($sAskedDbName);

        if ($bCronjob) {
            Task::create([
                'command' => 'mysql:backup',
                'parameter' => [
                    '--allDatabases' => $bAllDatabases,
                    '--database' => $sAskedDbName,
                    '--storage' => $sUploadDriver,
                ],
                'frequency' => 'everyMinute'
            ]); // TODO schedule & create database for server tools in init command
        }
    }
}

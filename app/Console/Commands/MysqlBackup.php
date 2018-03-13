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

        $bAllDatabases = $this->option('allDatabases');

        if ($bAllDatabases) {
            $aParams[] = '--all-databases';
        }

        $sAskedDbName = '';
        if (!$bAllDatabases) {
            $sAskedDbName = $this->option('database');
        }

        $sUploadDriver = $this->choiceOption('storage', ['local', 'spaces']);

        $bCronjob = $this->option('cronjob');

        $sFileName = ($bAllDatabases ? 'alldatabases' : $sAskedDbName) . '_' . date('d-m-Y_H-i-s') . '.sql';

        shell_exec('mysqldump ' . getMysqlCredentials() . ' ' . implode(' ', $aParams) . ($bAllDatabases ? '' : ' ' . $sAskedDbName) . ' > ' . base_path($sFileName));

        Storage::disk($sUploadDriver)->put(buildBackupPath('mysql', $sFileName), file_get_contents(base_path($sFileName)));

        unlink(base_path($sFileName));

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

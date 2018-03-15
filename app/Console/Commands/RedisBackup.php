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
        $sUploadDriver = $this->choiceOption('storage', 'Upload to local or digitalocean spaces?', ['local', 'spaces']);

        if ($sUploadDriver === 'spaces' && !isSpacesSet()) {
            $this->abort('Spaces is not set up correctly');
        }

        $bCronjob = $this->booleanOption('cronjob', 'Set up a cronjob that runs daily?');

        $sFileName = date('d-m-Y_H-i-s') . '.json';

        shell_exec('redis-dump -a \'' . getRedisPassword() . '\' > ' . base_path($sFileName));

        Storage::disk($sUploadDriver)->put(buildBackupPath('redis', $sFileName), file_get_contents(base_path($sFileName)));

        unlink(base_path($sFileName));

        if ($bCronjob) {
            Task::create([
                'command' => 'redis:backup',
                'parameter' => [
                    '--storage' => $sUploadDriver,
                ],
                'frequency' => 'daily'
            ]);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\MysqlCreateTaskManager;
use App\Console\ModCommand;
use Illuminate\Console\Command;

class MysqlCreate extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql:create';

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

        $sAskedDbName = $this->ask('Database Name?');
        $bNewUserAndAccess = $this->confirm('Create new user & give him access to new database?', true);

        (new MysqlCreateTaskManager([
            'database'         => $sAskedDbName,
            'newUserAndAccess' => $bNewUserAndAccess,
        ]))->work();
    }
}

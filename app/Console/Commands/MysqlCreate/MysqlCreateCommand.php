<?php

namespace App\Console\Commands\MysqlCreate;

use App\Console\Command;

class MysqlCreateCommand extends Command
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
        $sAskedDbName      = $this->ask('Database Name?');
        $bNewUserAndAccess = $this->confirm('Create new user & give him access to new database?', true);

        MysqlCreateTaskManager::work([
            'database'         => $sAskedDbName,
            'newUserAndAccess' => $bNewUserAndAccess,
        ]);
    }
}

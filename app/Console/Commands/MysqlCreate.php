<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MysqlCreate extends Command
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
        $sAskedDbName = $this->ask('Database Name?');
        $sDatabase = createMysqlDatabase($sAskedDbName);
        $this->addToReturn('Created new database: ' . $sDatabase);

        $bNewUserAndAccess = $this->confirm('Create new user & give him access to new database?');
        if ($bNewUserAndAccess) {
            $aUser = createMysqlUserAndGiveAccessToDatabase($sDatabase);
            $this->addToReturn('Created new user');
            $this->addToReturn('Username: ' . $aUser['user']);
            $this->addToReturn('Password: ' . $aUser['password']);
        }

        echo $this->getReturn();
    }
}

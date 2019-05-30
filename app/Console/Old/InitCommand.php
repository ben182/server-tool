<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

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
        $sDatabase = createMysqlDatabase('stool');
        $aUser     = createMysqlUserAndGiveAccessToDatabase($sDatabase);

        editEnvKey(base_path('.env'), 'DB_DATABASE', $sDatabase);
        editEnvKey(base_path('.env'), 'DB_USERNAME', $aUser['user']);
        editEnvKey(base_path('.env'), 'DB_PASSWORD', $aUser['password']);

        editEnvKey(base_path('.env'), 'APP_ENV', 'production');
        // editEnvKey(base_path('.env'), 'APP_DEBUG', 'false');
    }
}

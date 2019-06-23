<?php

namespace App\Console\Commands\Installation;

use App\Helper\Env;
use App\Console\Command;

class InstallationInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $env;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Env $env)
    {
        parent::__construct();

        $this->env   = $env;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $databaseName = $this->shell->mysql()->createDatabase('stool');
        $user         = $this->shell->mysql()->createUser()->giveAccessToDatabase($databaseName);

        $this->env->setKey('DB_DATABASE', $databaseName);
        $this->env->setKey('DB_USERNAME', $user->getName());
        $this->env->setKey('DB_PASSWORD', $user->getPassword());

        $this->env->setKey('APP_ENV', 'production');
    }
}

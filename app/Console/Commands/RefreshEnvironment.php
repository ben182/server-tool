<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'environment:refresh';

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
        $this->task('Refreshing Droplet ID', function () {
            shell_exec('bash ' . scripts_path() . 'partials/setDropletId.sh');
        });

        $this->task('Refreshing MySQL Password', function () {
            $NEW_DB_PASS = str_random(16);
            buildMysqlCommand("UPDATE mysql.user SET authentication_string=PASSWORD('$NEW_DB_PASS') WHERE User='root'");
            shell_exec("service mysql restart");
            editConfigKey('mysql.password', $NEW_DB_PASS);
        });

    }
}

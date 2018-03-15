<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Helper\Domain;
use Illuminate\Console\Command;

class DeleteVhostCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vhost:delete';

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
        $sDomain = $this->ask('Domain?');

        $oDomain = new Domain($sDomain);

        if ($oDomain->doesNotExist()) {
            $this->abort('The domain directory does not exist');
        }

        $bDeleteDir = $this->confirm('Delete folder in /var/www?', 0);

        $this->task('Deleting vHost', function () use ($sDomain) {
            try {
                shell_exec("a2dissite $sDomain.conf -q 2>&1");
                shell_exec("a2dissite $sDomain-le-ssl.conf -q 2>&1");

                unlink("/etc/apache2/sites-available/$sDomain.conf");
                unlink("/etc/apache2/sites-available/$sDomain-le-ssl.conf");
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });

        if ($bDeleteDir) {
            $this->task('Deleting html folder', function () use ($sDomain) {
                try {
                    shell_exec("rm -r /var/www/$sDomain 2>&1");
                } catch (\Exception $e) {
                    echo $e;
                    return false;
                }

                return true;
            });
        }

        $this->task('Clean up & Finishing', function () {
            try {
                $this->fixApachePermissions()->restartApache();
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });
    }
}

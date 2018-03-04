<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddVhostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vhost:add';

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

        $this->task('Creating vHost', function () use ($sDomain) {

            try {

                copy(templates_path() . 'apache/vhost.conf', "/etc/apache2/sites-available/$sDomain.conf");

                replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'DOCUMENT_ROOT', $sDomain);

            } catch(\Exception $e) {
                echo $e;
                return FALSE;
            }

            return TRUE;
        });

        $bWwwAlias = $this->confirm('www Alias?', 1);

        $this->task('Configuring vHost', function () use ($sDomain, $bWwwAlias) {

            try {

                if (!$bWwwAlias) {
                    replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'ServerAlias www.SERVER_NAME', '');
                }

                replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'SERVER_NAME', $sDomain);
                replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'NAME', $sDomain);

                shell_exec("a2ensite $sDomain.conf -q 2>&1");

                if (!file_exists("/var/www/$sDomain/html")) {
                    mkdir("/var/www/$sDomain/html", 755, TRUE);
                }

            } catch(\Exception $e) {
                echo $e;
                return FALSE;
            }

            return TRUE;
        });

        $bSsl = $this->confirm('SSL?', 1);
        if ($bSsl) {
            $sEmail = $this->ask('Email?');

            $this->task('Setting up SSL', function () use ($sDomain, $sEmail) {

                try {

                    echo shell_exec("certbot --non-interactive --agree-tos --email $sEmail --apache --domains $sDomain --quiet 2>&1");

                } catch(\Exception $e) {
                    echo $e;
                    return FALSE;
                }

                $this->line('Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d=' . $sDomain);
                return TRUE;
            });
        }

        $sHtaccess = $this->choice('htaccess?', ['Non SSL to SSL and www to non www', 'www to non www', 'Nothing']);

        if ($sHtaccess !== 'Nothing') {
            $this->task('Configuring htaccess', function () use ($sDomain, $sHtaccess) {

                try {

                    switch ($sHtaccess) {
                        case 'Non SSL to SSL and www to non www':

                            copy(templates_path() . 'apache/nonSSL_to_SSL_and_www_to_nonwww.htaccess', "/var/www/$sDomain/html/.htaccess");
                            break;

                        case 'www to non www':

                            copy(templates_path() . 'apache/www_to_nonwww.htaccess', "/var/www/$sDomain/html/.htaccess");
                            break;

                        default:
                            break;
                    }

                } catch(\Exception $e) {
                    echo $e;
                    return FALSE;
                }

                return TRUE;
            });

        }

        $this->task('Clean up & Finishing', function () {

            try {

                apache_permissions();
                echo shell_exec('service apache2 reload 2>&1');

            } catch(\Exception $e) {
                echo $e;
                return FALSE;
            }

            return TRUE;
        });

    }
}

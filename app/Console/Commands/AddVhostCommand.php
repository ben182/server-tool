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
    protected $signature = 'vhost:add {dev?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $bDev = FALSE;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->argument('dev')) {
            $this->bDev = TRUE;
        }
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

            $this->task('Setting up SSL', function () use ($sDomain, $sEmail, $bWwwAlias) {

                try {

                    echo shell_exec("certbot --non-interactive --agree-tos --email $sEmail --apache -d $sDomain" . ($bWwwAlias ? " -d www.$sDomain" : '') . " --quiet" . ($this->bDev ? ' --staging' : '') . " 2>&1");
                    /*
                    htaccess
                    RewriteEngine on
                    RewriteCond %{HTTP_HOST} ^(www\.)(.+) [OR]
                    RewriteCond %{HTTPS} off
                    RewriteCond %{HTTP_HOST} ^(www\.)?(.+)
                    RewriteRule ^ https://%2%{REQUEST_URI} [R=301,L]

                    RewriteEngine On

                    # match any URL with www and rewrite it to https without the www
                    RewriteCond %{HTTP_HOST} ^(www\.)(.*) [NC]
                    RewriteRule (.*) https://%2%{REQUEST_URI} [L,R=301]
                    */

                } catch(\Exception $e) {
                    echo $e;
                    return FALSE;
                }

                $this->line('Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d=' . $sDomain);
                return TRUE;
            });
        }

        $sHtaccess = $this->choice('htaccess?', ['Non SSL to SSL and www to non www', 'www to non www', 'Nothing']);

        $this->task('Configuring htaccess', function () use ($sDomain, $sHtaccess) {

            try {

                switch ($sHtaccess) {
                    case 'Non SSL to SSL and www to non www':

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'INCLUDE', 'Include ' . templates_path() . 'redirectSslAndWww.80.conf');

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain-le-ssl.conf", '</VirtualHost>', 'Include ' . templates_path() . 'redirectSslAndWww.443.conf' . PHP_EOL . '</VirtualHost>');

                        break;

                    case 'www to non www':

                        copy(templates_path() . 'apache/www_to_nonwww.htaccess', "/var/www/$sDomain/html/.htaccess");
                        break;

                    default:

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'INCLUDE', '');
                        break;
                }

            } catch(\Exception $e) {
                echo $e;
                return FALSE;
            }

            return TRUE;
        });



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

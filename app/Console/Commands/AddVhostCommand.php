<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use Illuminate\Console\Command;

class AddVhostCommand extends ModCommand
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

    protected $bDev = false;

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
        if ($this->argument('dev')) {
            $this->bDev = true;
        }

        $sDomain = $this->ask('Domain?');

        $this->task('Creating vHost', function () use ($sDomain) {
            try {
                copy(templates_path() . 'apache/vhost.conf', "/etc/apache2/sites-available/$sDomain.conf");

                replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'DOCUMENT_ROOT', $sDomain);
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });

        $bWwwAlias = $this->confirm('www Alias?', 1);

        $this->task('Configuring vHost', function () use ($sDomain, $bWwwAlias) {
            try {
                if (!$bWwwAlias) {
                    replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'ServerAlias www.SERVER_NAME', '');
                }

                replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'SERVER_NAME', $sDomain);
                replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'NAME', $sDomain);

                quietCommand("a2ensite $sDomain.conf -q");

                if (!file_exists("/var/www/$sDomain/html")) {
                    mkdir("/var/www/$sDomain/html", 755, true);
                }
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });

        $bSsl = $this->confirm('SSL?', 1);
        if ($bSsl) {
            $sEmail = $this->ask('Email?');

            $this->task('Setting up SSL', function () use ($sDomain, $sEmail, $bWwwAlias) {
                try {
                    quietCommand("certbot --non-interactive --agree-tos --email $sEmail --apache -d $sDomain" . ($bWwwAlias ? " -d www.$sDomain" : '') . ' --quiet' . ($this->bDev ? ' --staging' : ''));
                } catch (\Exception $e) {
                    echo $e;
                    return false;
                }

                $this->line('Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d=' . $sDomain);
                return true;
            });
        }

        $sHtaccess = $this->choice('htaccess?', [
            'Non SSL to SSL and www to non www',
            'Non SSL to SSL',
            'www to non www',
            'Nothing',
        ]);

        $this->task('Configuring htaccess', function () use ($sDomain, $sHtaccess) {
            try {
                switch ($sHtaccess) {
                    case 'Non SSL to SSL and www to non www':

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.80.conf' . PHP_EOL . '</VirtualHost>');

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain-le-ssl.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.443.conf' . PHP_EOL . '</VirtualHost>');

                        break;

                    case 'www to non www':

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/www_to_nonwww.htaccess' . PHP_EOL . '</VirtualHost>');

                        break;

                    case 'Non SSL to SSL':

                        replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/nonSSL_to_SSL.htaccess' . PHP_EOL . '</VirtualHost>');

                        break;

                    default:
                        break;
                }
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });

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

<?php

use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\RouteController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('gad:add', function () {

    $dir = $this->ask('Path (from /var/www/)?');
    $branch = $this->ask('Branch?');
    $reset = (int) $this->confirm('Hard Reset?', 1);

    $aRoute = RouteController::add([
        'dir' => $dir,
        'branch' => $branch,
        'reset' => $reset,
    ]);

    $this->line('Route');
    $this->line(route('api.gad.deploy', [
        'id' => $aRoute['id']
    ]));
    $this->line('Secret');
    $this->line($aRoute['secret']);
});

Artisan::command('gad:list', function () {

    $aRoutes = RouteController::getAll();

    foreach ($aRoutes as $sKey => $aRoute) {
        $this->line($sKey . ':');

        $this->line(' - Url: ' . route('api.gad.deploy', [
            'id' => $aRoute['id']
        ]));
        foreach ($aRoute as $sKey => $sItem) {
            $this->line(' - ' . $sKey . ': ' . $sItem);
        }
    }
});

Artisan::command('vhost:add', function () {

    $sDomain = $this->ask('Domain?');

    copy(templates_path() . 'apache/vhost.conf', "/etc/apache2/sites-available/$sDomain.conf");

    replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'DOCUMENT_ROOT', $sDomain);

    $bWwwAlias = $this->confirm('www Alias?', 1);

    if (!$bWwwAlias) {
        replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'ServerAlias www.SERVER_NAME', '');
    }

    replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'SERVER_NAME', $sDomain);
    replace_string_in_file("/etc/apache2/sites-available/$sDomain.conf", 'NAME', $sDomain);

    echo shell_exec("a2ensite $DOMAIN.conf 2>&1");

    mkdir("/var/www/$DOMAIN/html", 755, TRUE);

    $bSsl = $this->confirm('SSL?', 1);

    if ($bSsl) {
        $sEmail = $this->ask('Email?');

        echo shell_exec("certbot --non-interactive --agree-tos --email $sEmail --apache --domains $sDomain 2>&1");
    }

    $sHtaccess = $this->choice('htaccess?', ['Non SSL to SSL and www to non www', 'www to non www', 'Nothing']);

    switch ($sHtaccess) {
        case 'Non SSL to SSL and www to non www':

            copy(templates_path() . 'apache/nonSSL_to_SSL_and_www_to_nonwww.htaccess', "/var/www/$DOMAIN/html/.htaccess");
            break;

        case 'www to non www':

            copy(templates_path() . 'apache/www_to_nonwww.htaccess', "/var/www/$DOMAIN/html/.htaccess");
            break;

        default:
            break;
    }

    apache_permissions();
    echo shell_exec('service apache2 reload 2>&1');
});

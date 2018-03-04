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

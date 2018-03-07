<?php


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

Artisan::command('test', function () {
    preg_match('/(?<=MAIL_DRIVER=).*/', file_get_contents(base_path('.env')), $match);
    dd($match);
});

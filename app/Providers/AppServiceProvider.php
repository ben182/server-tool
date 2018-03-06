<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Command;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Command::macro('abort', function ($sMessage) {
            $this->error($sMessage);
            exit();
        });
        Command::macro('addToReturn', function ($sMessage) {
            $this->aReturn[] = $sMessage;
        });
        Command::macro('getReturn', function ($sMessage) {
            return implode("\n", $this->aReturn);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

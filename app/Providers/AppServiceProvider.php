<?php

namespace App\Providers;

use App\Observers\RepositoryObserver;
use App\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Repository::observe(RepositoryObserver::class);
        Route::model('repository', \App\Repository::class);

        Command::macro('abort', function ($sMessage) {
            $this->error($sMessage);
            exit();
        });
        Command::macro('fixApachePermissions', function () {
            echo shell_exec('chown -R www-data:www-data /var/www 2>&1');
            echo shell_exec('chmod -R 755 /var/www 2>&1');
            echo shell_exec('chmod g+s /var/www 2>&1');
            echo shell_exec('chmod -R 700 /var/www/.ssh 2>&1');
            return $this;
        });
        Command::macro('restartApache', function () {
            echo shell_exec('service apache2 reload 2>&1');
            return $this;
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

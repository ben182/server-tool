<?php

namespace App\Providers;

use App\Console\Commands\Tasks\Shell\ShellTask;
use App\Helper\Shell;
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
            quietCommand('chown -R www-data:www-data /var/www');
            quietCommand('chmod -R 755 /var/www');
            quietCommand('chmod g+s /var/www');
            quietCommand('chmod -R 700 /var/www/.ssh');
            return $this;
        });
        Command::macro('restartApache', function () {
            quietCommand('service apache2 reload');
            return $this;
        });
        $this->app->singleton('Shell', function ($app) {
            return new Shell();
        });

        $this->app->singleton('ShellTask', function ($app) {
            return new ShellTask();
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

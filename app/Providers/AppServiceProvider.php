<?php

namespace App\Providers;

use App\Helper\Env;
use App\Repository;
use App\Helper\Config;
use App\Helper\Hardware;
use App\Helper\Shell\Mysql;
use App\Helper\Shell\Shell;
use App\Helper\Shell\Cronjob;
use App\Helper\Shell\Service;
use App\Helper\Shell\Environment;
use App\Observers\RepositoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Repository::observe(RepositoryObserver::class);

        $this->app->singleton('stool-env', Env::class);

        $this->app->singleton('stool-config', Config::class);

        $this->app->singleton('stool-shell', Shell::class);
        $this->app->singleton('stool-shell-cronjob', Cronjob::class);
        $this->app->singleton('stool-shell-environment', Environment::class);
        $this->app->singleton('stool-shell-mysql', Mysql::class);
        $this->app->singleton('stool-shell-service', Service::class);

        $this->app->singleton('stool-hardware', Hardware::class);
    }
}

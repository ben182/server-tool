<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\RepositoryObserver;
use App\Repository;
use App\Helper\Env;
use App\Helper\Shell\Shell;
use App\Helper\Shell\Cronjob;
use App\Helper\Shell\Environment;
use App\Helper\Shell\Mysql;
use App\Helper\Config;

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

        $this->app->singleton('env', Env::class);

        $this->app->singleton('config', Config::class);

        $this->app->singleton('shell', Shell::class);
        $this->app->singleton('shell-cronjob', Cronjob::class);
        $this->app->singleton('shell-environment', Environment::class);
        $this->app->singleton('shell-mysql', Mysql::class);
    }
}

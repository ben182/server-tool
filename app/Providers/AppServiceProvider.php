<?php

namespace App\Providers;

use App\Helper\Env;
use App\Helper\Check;
use App\Helper\Apache;
use App\Helper\Config;
use App\Helper\Github;
use App\Helper\Hardware;
use App\Helper\Initials;
use App\Helper\Password;
use App\Helper\Increment;
use App\Helper\FloatingIp;
use App\Helper\Shell\Mysql;
use App\Helper\Shell\Shell;
use App\Helper\Shell\Cronjob;
use App\Helper\Shell\Service;
use Illuminate\Console\Command;
use App\Helper\Shell\Environment;
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
        $this->app->singleton('stool-env', Env::class);

        $this->app->singleton('stool-config', Config::class);

        $this->app->singleton('stool-shell', Shell::class);
        $this->app->singleton('stool-shell-cronjob', Cronjob::class);
        $this->app->singleton('stool-shell-environment', Environment::class);
        $this->app->singleton('stool-shell-mysql', Mysql::class);
        $this->app->singleton('stool-shell-service', Service::class);

        $this->app->singleton('stool-hardware', Hardware::class);
        $this->app->singleton('stool-check', Check::class);
        $this->app->singleton('stool-password', Password::class);
        $this->app->singleton('stool-increment', Increment::class);
        $this->app->singleton('stool-github', Github::class);
        $this->app->singleton('stool-apache', Apache::class);
        $this->app->singleton('stool-floating-ip', FloatingIp::class);
        $this->app->singleton('stool-initials', Initials::class);

        Command::macro('abort', function ($sMessage) {
            $this->error($sMessage);
            exit();
        });
        Command::macro('break', function () {
            $this->line('');
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider as IlluminateConsoleSupportServiceProvider;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;

class ConsoleSupportServiceProvider extends IlluminateConsoleSupportServiceProvider
{
/**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ComposerServiceProvider::class,
    ];

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        if (!$app->environment('production')) {
            $this->providers = [
                ArtisanServiceProvider::class,
                MigrationServiceProvider::class,
                ComposerServiceProvider::class,
            ];
        }

        parent::__construct($app);
    }
}

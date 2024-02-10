<?php

namespace Jetcod\Eloquent;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/Config/Eloquent.php' => config_path('eloquent.php'),
        ], 'eloquent-key-generator-config');

        $this->mergeConfigFrom(__DIR__ . '/Config/Eloquent.php', 'eloquent');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->bind('PrimaryKeyGenerator', function (Application $app) {
            return $app->make(config('eloquent.customModel.generator'));
        });

        $this->app->alias('PrimaryKeyGenerator', \Jetcod\Eloquent\Facades\PrimaryKeyGenerator::class);
    }
}

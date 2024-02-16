<?php

namespace Jetcod\Eloquent;

use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake;
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
        $this->app->singleton(Snowflake::class, function (Application $app) {
            return new Snowflake(
                config('Eloquent.snowflake.datacenter'),
                config('Eloquent.snowflake.worker'),
            );
        });

        $this->app->singleton(SequenceResolver::class, config('Eloquent.snowflake.sequence_resolver'));
    }
}

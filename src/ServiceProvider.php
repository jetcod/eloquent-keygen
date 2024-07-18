<?php

namespace Jetcod\Eloquent;

use Godruoyi\Snowflake\FileLockResolver;
use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Jetcod\Eloquent\Facades\PrimaryKeyGenerator as PrimaryKeyGeneratorFacade;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/Config/snowflake.php' => config_path('snowflake.php'),
        ], 'eloquent-key-generator-config');

        $this->mergeConfigFrom(__DIR__ . '/Config/snowflake.php', 'snowflake');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerSnowFlake();
        $this->registerSequenceResolver();
        $this->registerPrimaryKeyGenerator();
    }

    private function registerSnowFlake(): void
    {
        $this->app->singleton(Snowflake::class, function (Application $app) {
            return new Snowflake(
                config('snowflake.attributes.datacenter'),
                config('snowflake.attributes.worker'),
            );
        });
    }

    private function registerSequenceResolver(): void
    {
        $this->app->singleton(SequenceResolver::class, function (Application $app) {
            $resolverClass = config('snowflake.attributes.sequence_resolver');

            switch ($resolverClass) {
                case LaravelSequenceResolver::class:
                    return $app->make(LaravelSequenceResolver::class, [Repository::class]);

                case RandomSequenceResolver::class:
                    return $app->make($resolverClass);

                case FileLockResolver::class:
                    $path = config('snowflake.attributes.file_lock_directory');

                    if (null === $path) {
                        $path = $app->storagePath() . '/snowflake';
                        mkdir($path, 0755, true);
                    }

                    return $app->make($resolverClass, [$path]);

                default:
                    throw new \InvalidArgumentException("Invalid sequence resolver class: {$resolverClass}");
            }
        });
    }

    private function registerPrimaryKeyGenerator(): void
    {
        $this->app->singleton('snowflake-id', function ($app) {
            return $app->make(PrimaryKeyGenerator::class, [
                Snowflake::class,
                SequenceResolver::class,
            ]);
        });

        $this->app->alias('snowflake-id', PrimaryKeyGeneratorFacade::class);
    }
}

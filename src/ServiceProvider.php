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
        $this->registerSnowFlake();
        $this->registerSequenceResolver();
        $this->registerPrimaryKeyGenerator();
    }

    private function registerSnowFlake(): void
    {
        $this->app->singleton(Snowflake::class, function (Application $app) {
            return new Snowflake(
                config('eloquent.snowflake.datacenter'),
                config('eloquent.snowflake.worker'),
            );
        });
    }

    private function registerSequenceResolver(): void
    {
        $this->app->singleton(SequenceResolver::class, function (Application $app) {
            $resolverClass = config('eloquent.snowflake.sequence_resolver');

            switch ($resolverClass) {
                case LaravelSequenceResolver::class:
                    return $app->make(LaravelSequenceResolver::class, [Repository::class]);

                case RandomSequenceResolver::class:
                    return $app->make($resolverClass);

                case FileLockResolver::class:
                    $path = config('eloquent.snowflake.file_lock_directory');

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
        $this->app->bind(PrimaryKeyGenerator::class, function (Application $app) {
            return $app->make(PrimaryKeyGenerator::class, [Snowflake::class, SequenceResolver::class]);
        });
    }
}

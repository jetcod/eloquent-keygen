<?php

namespace Jetcod\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Jetcod\Eloquent\Model as EloquentModel;

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
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/Eloquent.php', 'eloquent');

        $this->app->singleton(Model::class, config('eloquent.customModel.generator', EloquentModel::class));
    }
}

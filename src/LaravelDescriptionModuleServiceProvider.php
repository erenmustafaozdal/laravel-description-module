<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Illuminate\Support\ServiceProvider;

class LaravelDescriptionModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/laravel-description-module.php' => config_path('laravel-description-module.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('ErenMustafaOzdal\LaravelModulesBase\LaravelModulesBaseServiceProvider');
        $this->app->register('Baum\Providers\BaumServiceProvider');

        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-description-module.php', 'laravel-description-module'
        );

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-description-module.url.description'),  'App\Description');
        $router->model(config('laravel-description-module.url.description_category'),  'App\DescriptionCategory');
    }
}

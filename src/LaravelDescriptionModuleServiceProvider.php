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
        // merge default configs with publish configs
        $this->mergeDefaultConfig();

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-description-module.url.description'),  'App\Description');
        $router->model(config('laravel-description-module.url.description_category'),  'App\DescriptionCategory');
    }

    /**
     * merge default configs with publish configs
     */
    protected function mergeDefaultConfig()
    {
        $config = $this->app['config']->get('laravel-description-module', []);
        $default = require __DIR__.'/../config/default.php';

        // admin description category routes
        $route = $config['routes']['admin']['description_category'];
        $default['routes']['admin']['description_category'] = $route;
        // admin description routes
        $route = $config['routes']['admin']['description'];
        $default['routes']['admin']['description'] = $route;
        $default['routes']['admin']['description_publish'] = $route;
        $default['routes']['admin']['description_notPublish'] = $route;
        // admin sub description categories nested categories
        $route = $config['routes']['admin']['nested_sub_categories'];
        $default['routes']['admin']['category_categories'] = $route;
        // admin sub description categories descriptions
        $route = $config['routes']['admin']['sub_category_descriptions'];
        $default['routes']['admin']['category_descriptions'] = $route;
        $default['routes']['admin']['category_descriptions_publish'] = $route;
        $default['routes']['admin']['category_descriptions_notPublish'] = $route;

        // api description category routes
        $apiCat = $config['routes']['api']['description_category'];
        $default['routes']['api']['description_category'] = $apiCat;
        // api sub description categories nested categories
        $apiSubCat = $config['routes']['api']['nested_sub_categories'];
        $default['routes']['api']['category_categories_index'] = $apiSubCat;

        $default['routes']['api']['description_category_models'] = $apiCat || $apiSubCat;
        $default['routes']['api']['description_category_move'] = $apiCat || $apiSubCat;
        $default['routes']['api']['description_category_detail'] = $apiCat || $apiSubCat;

        // api description routes
        $model = $config['routes']['api']['description'];
        $default['routes']['api']['description'] = $model;
        // api sub description categories descriptions
        $subModel = $config['routes']['api']['sub_category_descriptions'];
        $default['routes']['api']['category_descriptions_index'] = $subModel;

        $default['routes']['api']['description_group'] = $model || $subModel;
        $default['routes']['api']['description_detail'] = $model || $subModel;
        $default['routes']['api']['description_fastEdit'] = $model || $subModel;
        $default['routes']['api']['description_publish'] = $model || $subModel;
        $default['routes']['api']['description_notPublish'] = $model || $subModel;
        $default['routes']['api']['description_removePhoto'] = $model || $subModel;

        $config['routes'] = $default['routes'];


        // model photo uploads
        $config['description']['uploads']['photo']['relation'] = $default['description']['uploads']['photo']['relation'];
        $config['description']['uploads']['photo']['relation_model'] = $default['description']['uploads']['photo']['relation_model'];
        $config['description']['uploads']['photo']['type'] = $default['description']['uploads']['photo']['type'];
        $config['description']['uploads']['photo']['column'] = $default['description']['uploads']['photo']['column'];
        // model multiple photo uploads
        $config['description']['uploads']['multiple_photo']['relation'] = $default['description']['uploads']['multiple_photo']['relation'];
        $config['description']['uploads']['multiple_photo']['relation_model'] = $default['description']['uploads']['multiple_photo']['relation_model'];
        $config['description']['uploads']['multiple_photo']['type'] = $default['description']['uploads']['multiple_photo']['type'];
        $config['description']['uploads']['multiple_photo']['column'] = $default['description']['uploads']['multiple_photo']['column'];

        $this->app['config']->set('laravel-description-module', $config);
    }
}

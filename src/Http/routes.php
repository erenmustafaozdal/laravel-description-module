<?php
//max level nested function 100 hatasını düzeltiyor
ini_set('xdebug.max_nesting_level', 300);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
/*==========  Document Category Module  ==========*/
Route::group([
    'prefix' => config('laravel-description-module.url.admin_url_prefix'),
    'middleware' => config('laravel-description-module.url.middleware'),
    'namespace' => config('laravel-description-module.controller.description_category_admin_namespace')
], function()
{
    if (config('laravel-description-module.routes.admin.description_category')) {
        Route::resource(config('laravel-description-module.url.description_category'), config('laravel-description-module.controller.description_category'), [
            'names' => [
                'index'         => 'admin.description_category.index',
                'create'        => 'admin.description_category.create',
                'store'         => 'admin.description_category.store',
                'show'          => 'admin.description_category.show',
                'edit'          => 'admin.description_category.edit',
                'update'        => 'admin.description_category.update',
                'destroy'       => 'admin.description_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-description-module.routes.admin.category_categories')) {
        Route::group(['middleware' => 'nested_model:DocumentCategory'], function() {
            Route::resource(config('laravel-description-module.url.description_category') . '/{id}/' . config('laravel-description-module.url.description_category'), config('laravel-description-module.controller.description_category'), [
                'names' => [
                    'index' => 'admin.description_category.description_category.index',
                    'create' => 'admin.description_category.description_category.create',
                    'store' => 'admin.description_category.description_category.store',
                    'show' => 'admin.description_category.description_category.show',
                    'edit' => 'admin.description_category.description_category.edit',
                    'update' => 'admin.description_category.description_category.update',
                    'destroy' => 'admin.description_category.description_category.destroy',
                ]
            ]);
        });
    }
});

/*==========  Document Module  ==========*/
Route::group([
    'prefix'        => config('laravel-description-module.url.admin_url_prefix'),
    'middleware'    => config('laravel-description-module.url.middleware'),
    'namespace'     => config('laravel-description-module.controller.description_admin_namespace')
], function()
{
    // admin publish description
    if (config('laravel-description-module.routes.admin.description_publish')) {
        Route::get('description/{' . config('laravel-description-module.url.description') . '}/publish', [
            'as'                => 'admin.description.publish',
            'uses'              => config('laravel-description-module.controller.description').'@publish'
        ]);
    }
    // admin not publish description
    if (config('laravel-description-module.routes.admin.description_notPublish')) {
        Route::get('description/{' . config('laravel-description-module.url.description') . '}/not-publish', [
            'as'                => 'admin.description.notPublish',
            'uses'              => config('laravel-description-module.controller.description').'@notPublish'
        ]);
    }
    if (config('laravel-description-module.routes.admin.description')) {
        Route::resource(config('laravel-description-module.url.description'), config('laravel-description-module.controller.description'), [
            'names' => [
                'index'         => 'admin.description.index',
                'create'        => 'admin.description.create',
                'store'         => 'admin.description.store',
                'show'          => 'admin.description.show',
                'edit'          => 'admin.description.edit',
                'update'        => 'admin.description.update',
                'destroy'       => 'admin.description.destroy',
            ]
        ]);
    }

    /*==========  Category descriptions  ==========*/
    // admin publish description
    if (config('laravel-description-module.routes.admin.category_descriptions_publish')) {
        Route::get(config('laravel-description-module.url.description_category') . '/{id}/' . config('laravel-description-module.url.description') . '/{' . config('laravel-description-module.url.description') . '}/publish', [
            'middleware'        => 'related_model:DocumentCategory,descriptions',
            'as'                => 'admin.description_category.description.publish',
            'uses'              => config('laravel-description-module.controller.description').'@publish'
        ]);
    }
    // admin not publish description
    if (config('laravel-description-module.routes.admin.category_descriptions_notPublish')) {
        Route::get(config('laravel-description-module.url.description_category') . '/{id}/' . config('laravel-description-module.url.description') . '/{' . config('laravel-description-module.url.description') . '}/not-publish', [
            'middleware'        => 'related_model:DocumentCategory,descriptions',
            'as'                => 'admin.description_category.description.notPublish',
            'uses'              => config('laravel-description-module.controller.description').'@notPublish'
        ]);
    }

    // category descriptions
    if (config('laravel-description-module.routes.admin.category_descriptions')) {
        Route::group(['middleware' => 'related_model:DocumentCategory,descriptions'], function() {
            Route::resource(config('laravel-description-module.url.description_category') . '/{id}/' . config('laravel-description-module.url.description'), config('laravel-description-module.controller.description'), [
                'names' => [
                    'index' => 'admin.description_category.description.index',
                    'create' => 'admin.description_category.description.create',
                    'store' => 'admin.description_category.description.store',
                    'show' => 'admin.description_category.description.show',
                    'edit' => 'admin.description_category.description.edit',
                    'update' => 'admin.description_category.description.update',
                    'destroy' => 'admin.description_category.description.destroy',
                ]
            ]);
        });
    }
});



/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
/*==========  Document Category Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-description-module.url.middleware'),
    'namespace'     => config('laravel-description-module.controller.description_category_api_namespace')
], function()
{
    // api description category
    if (config('laravel-description-module.routes.api.description_category_models')) {
        Route::post('description-category/models', [
            'as'                => 'api.description_category.models',
            'uses'              => config('laravel-description-module.controller.description_category_api').'@models'
        ]);
    }
    // api description category move
    if (config('laravel-description-module.routes.api.description_category_move')) {
        Route::post('description-category/{id}/move', [
            'as'                => 'api.description_category.move',
            'uses'              => config('laravel-description-module.controller.description_category_api').'@move'
        ]);
    }
    // data table detail row
    if (config('laravel-description-module.routes.api.description_category_detail')) {
        Route::get('description-category/{id}/detail', [
            'as'                => 'api.description_category.detail',
            'uses'              => config('laravel-description-module.controller.description_category_api').'@detail'
        ]);
    }
    // description category resource
    if (config('laravel-description-module.routes.api.description_category')) {
        Route::resource(config('laravel-description-module.url.description_category'), config('laravel-description-module.controller.description_category_api'), [
            'names' => [
                'index'         => 'api.description_category.index',
                'store'         => 'api.description_category.store',
                'update'        => 'api.description_category.update',
                'destroy'       => 'api.description_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-description-module.routes.api.category_categories_index')) {
        Route::get(config('laravel-description-module.url.description_category') . '/{id}/' . config('laravel-description-module.url.description_category'), [
            'middleware'        => 'nested_model:DocumentCategory',
            'as'                => 'api.description_category.description_category.index',
            'uses'              => config('laravel-description-module.controller.description_category_api').'@index'
        ]);
    }
});

/*==========  Document Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-description-module.url.middleware'),
    'namespace'     => config('laravel-description-module.controller.description_api_namespace')
], function()
{
    // api group action
    if (config('laravel-description-module.routes.api.description_group')) {
        Route::post('description/group-action', [
            'as'                => 'api.description.group',
            'uses'              => config('laravel-description-module.controller.description_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-description-module.routes.api.description_detail')) {
        Route::get('description/{id}/detail', [
            'as'                => 'api.description.detail',
            'uses'              => config('laravel-description-module.controller.description_api').'@detail'
        ]);
    }
    // get description category edit data for modal edit
    if (config('laravel-description-module.routes.api.description_fastEdit')) {
        Route::post('description/{id}/fast-edit', [
            'as'                => 'api.description.fastEdit',
            'uses'              => config('laravel-description-module.controller.description_api').'@fastEdit'
        ]);
    }
    // api publish description
    if (config('laravel-description-module.routes.api.description_publish')) {
        Route::post('description/{' . config('laravel-description-module.url.description') . '}/publish', [
            'as'                => 'api.description.publish',
            'uses'              => config('laravel-description-module.controller.description_api').'@publish'
        ]);
    }
    // api not publish description
    if (config('laravel-description-module.routes.api.description_notPublish')) {
        Route::post('description/{' . config('laravel-description-module.url.description') . '}/not-publish', [
            'as'                => 'api.description.notPublish',
            'uses'              => config('laravel-description-module.controller.description_api').'@notPublish'
        ]);
    }
    if (config('laravel-description-module.routes.api.description')) {
        Route::resource(config('laravel-description-module.url.description'), config('laravel-description-module.controller.description_api'), [
            'names' => [
                'index'         => 'api.description.index',
                'store'         => 'api.description.store',
                'update'        => 'api.description.update',
                'destroy'       => 'api.description.destroy',
            ]
        ]);
    }
    // category descriptions
    if (config('laravel-description-module.routes.api.category_descriptions_index')) {
        Route::get(config('laravel-description-module.url.description_category') . '/{id}/' . config('laravel-description-module.url.description'), [
            'middleware'        => 'related_model:DocumentCategory,descriptions',
            'as'                => 'api.description_category.description.index',
            'uses'              => config('laravel-description-module.controller.description_api').'@index'
        ]);
    }
});

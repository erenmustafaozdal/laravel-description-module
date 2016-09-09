<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'date_format'       => 'd.m.Y H:i:s',
    'icons' => [
        'description'           => 'icon-pencil',
        'description_category'  => 'icon-pencil'
    ],

    /*
    |--------------------------------------------------------------------------
    | URL config
    |--------------------------------------------------------------------------
    */
    'url' => [
        'description_category'      => 'description-categories',    // description categories url
        'description'               => 'descriptions',              // descriptions url
        'admin_url_prefix'          => 'admin',                     // admin dashboard url prefix
        'middleware'                => ['auth', 'permission']       // description module middleware
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller config
    | if you make some changes on controller, you create your controller
    | and then extend the Laravel Description Module Controller. If you don't need
    | change controller, don't touch this config
    |--------------------------------------------------------------------------
    */
    'controller' => [
        'description_category_admin_namespace' => 'ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers',
        'description_admin_namespace'          => 'ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers',
        'description_category_api_namespace'   => 'ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers',
        'description_api_namespace'            => 'ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers',
        'description_category'                 => 'DescriptionCategoryController',
        'description'                          => 'DescriptionController',
        'description_category_api'             => 'DescriptionCategoryApiController',
        'description_api'                      => 'DescriptionApiController'
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes on / off
    | if you don't use any route; set false
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'admin' => [
            'description_category'              => true,                // admin description category resource route
            'description'                       => true,                // admin description resource route
            'description_publish'               => true,                // admin description publish get route
            'description_notPublish'            => true,                // admin description not publish get route
            'category_categories'               => true,                // admin category nested categories resource route
            'category_descriptions'             => true,                // admin category descriptions resource route
            'category_descriptions_publish'     => true,                // admin category descriptions publish get route
            'category_descriptions_notPublish'  => true                 // admin category descriptions not publish get route
        ],
        'api' => [
            'description_category'              => true,                // api description category resource route
            'description_category_models'       => true,                // api description category model post route
            'description_category_move'         => true,                // api description category move post route
            'description'                       => true,                // api description resource route
            'description_group'                 => true,                // api description group post route
            'description_detail'                => true,                // api description detail get route
            'description_fastEdit'              => true,                // api description fast edit post route
            'description_publish'               => true,                // api description publish post route
            'description_notPublish'            => true,                // api description not publish post route
            'description_removePhoto'           => true,                // api description destroy photo post route
            'category_categories_index'         => true,                // api category nested categories index get route
            'category_descriptions_index'       => true,                // api category descriptions index get route
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | View config
    |--------------------------------------------------------------------------
    | dot notation of blade view path, its position on the /resources/views directory
    */
    'views' => [
        // description category view
        'description_category' => [
            'layout'    => 'laravel-modules-core::layouts.admin',                   // user layout
            'index'     => 'laravel-modules-core::description_category.index',      // get description category index view blade
            'create'    => 'laravel-modules-core::description_category.operation',  // get description category create view blade
            'show'      => 'laravel-modules-core::description_category.show',       // get description category show view blade
            'edit'      => 'laravel-modules-core::description_category.operation',  // get description category edit view blade
        ],
        // description view
        'description' => [
            'layout'    => 'laravel-modules-core::layouts.admin',                   // user layout
            'index'     => 'laravel-modules-core::description.index',               // get description index view blade
            'create'    => 'laravel-modules-core::description.operation',           // get description create view blade
            'show'      => 'laravel-modules-core::description.show',                // get description show view blade
            'edit'      => 'laravel-modules-core::description.operation',           // get description edit view blade
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - relation                        : file is in the relation table and what is relation type [false|hasOne|hasMany]
    | - relation_model                  : relation model [\App\Model etc...]
    | - type                            : file type [image,file]
    | - column                          : file database column
    | - path                            : file path
    | - max_size                        : file allowed maximum size
    | - max_file                        : maximum file count
    | - aspect_ratio                    : if file is image; crop aspect ratio
    | - mimes                           : file allowed mimes
    | - thumbnails                      : if file is image; its thumbnails options
    |
    | NOT: Thumbnails fotoğrafları yüklenirken bakılır:
    |       1. eğer post olarak x1, y1, x2, y2, width ve height değerleri gönderilmemiş ise bu değerlere göre
    |       thumbnails ayarlarında belirtilen resimleri sistem içine kaydeder.
    |       Yani bu değerler post edilmişse aşağıdaki değerleri yok sayar.
    |       2. Eğer yukarıdaki ilgili değerler post edilmemişse, thumbnails ayarlarında belirtilen değerleri
    |       dikkate alarak thumbnails oluşturur
    |
    |       Ölçü Belirtme:
    |       1. İstenen resmin width ve height değerleri verilerek istenen net bir ölçüde resimler oluşturulabilir
    |       2. Width değeri null verilerek, height değerine göre ölçeklenebilir
    |       3. Height değeri null verilerek, width değerine göre ölçeklenebilir
    |--------------------------------------------------------------------------
    */
    'description' => [
        'default_img_path'              => 'vendor/laravel-modules-core/assets/global/img/description',
        'uploads' => [
            // description photo options
            'photo' => [
                'relation'              => 'hasOne',
                'relation_model'        => '\App\DescriptionPhoto',
                'type'                  => 'image',
                'column'                => 'photo.photo',
                'path'                  => 'uploads/description',
                'max_size'              => '5120',
                'aspect_ratio'          => 16/9,
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'thumbnails' => [
                    'small'             => [ 'width' => 35, 'height' => null],
                    'normal'            => [ 'width' => 300, 'height' => null],
                    'big'               => [ 'width' => 800, 'height' => null],
                ]
            ],
            // description multiple photo options
            'multiple_photo' => [
                'relation'              => 'hasMany',
                'relation_model'        => '\App\DescriptionPhoto',
                'type'                  => 'image',
                'column'                => 'multiplePhoto.photo',
                'path'                  => 'uploads/description',
                'max_size'              => '3072',
                'max_file'              => 5,
                'aspect_ratio'          => 16/9,
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'thumbnails' => [
                    'small'             => [ 'width' => 35, 'height' => null],
                    'normal'            => [ 'width' => 300, 'height' => null],
                    'big'               => [ 'width' => 800, 'height' => null],
                ]
            ]
        ]
    ],
];

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
            'description_category'          => true,        // Is the route to be used categories admin
            'description'                   => true,        // Is the route to be used descriptions admin
            'nested_sub_categories'         => true,        // Did subcategory nested categories admin route will be used
            'sub_category_descriptions'     => true,        // Did subcategory description admin route will be used
        ],
        'api' => [
            'description_category'          => true,        // Is the route to be used categories api
            'description'                   => true,        // Is the route to be used descriptions api
            'nested_sub_categories'         => true,        // Did subcategory nested categories api route will be used
            'sub_category_descriptions'     => true,        // Did subcategory description api route will be used
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
        'default_img_path'          => 'vendor/laravel-modules-core/assets/global/img/description',
        'uploads' => [
            'path'                  => 'uploads/description',
            'max_size'              => '5120',
            'upload_max_file'       => 5,
            'photo_aspect_ratio'    => 16/9,
            'photo_mimes'           => 'jpeg,jpg,jpe,png',
            'photo_thumbnails' => [
                'small'             => [ 'width' => 35, 'height' => null],
                'normal'            => [ 'width' => 300, 'height' => null],
                'big'               => [ 'width' => 800, 'height' => null],
            ]
        ]
    ],






    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'description_category' => [
            'title'                 => 'Veri Kategorileri',
            'routes' => [
                'admin.description_category.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar veri kategorilerini veri tablosunda listeleyebilir.',
                ],
                'admin.description_category.create' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar veri kategorisi ekleyebilir',
                ],
                'admin.description_category.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar veri kategorisi bilgilerini görüntüleyebilir',
                ],
                'admin.description_category.edit' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar veri kategorisini düzenleyebilir',
                ],
                'admin.description_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar veri kategorisini silebilir',
                ],
                'api.description_category.models' => [
                    'title'         => 'Rolleri Listeleme',
                    'description'   => 'Bu izne sahip olanlar veri kategorilerini bazı seçim kutularında listeleyebilir',
                ],
                'api.description_category.move' => [
                    'title'         => 'Taşıma',
                    'description'   => 'Bu izne sahip olanlar veri kategorilerini taşıyarak yerini değiştirebilir.',
                ],
            ],
        ],
        'description' => [
            'title'                 => 'Veriler',
            'routes' => [
                'admin.description.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar verileri veri tablosunda listeleyebilir.',
                ],
                'admin.description.create' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar veri ekleyebilir',
                ],
                'admin.description.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar veri bilgilerini görüntüleyebilir',
                ],
                'admin.description.edit' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar veri bilgilerini düzenleyebilir',
                ],
                'admin.description.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar veriyi silebilir',
                ],
                'api.description.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar veriler veri tablosunda toplu işlem yapabilir',
                ],
                'api.description.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar veriler tablosunda detayını görebilir.',
                ]
            ],
        ]
    ],
];

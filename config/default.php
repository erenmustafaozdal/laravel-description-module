<?php

return [
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
            'description_category_detail'       => true,                // api description category detail post route
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
            ],
            // description multiple photo options
            'multiple_photo' => [
                'relation'              => 'hasMany',
                'relation_model'        => '\App\DescriptionPhoto',
                'type'                  => 'image',
                'column'                => 'multiplePhoto.photo',
            ]
        ]
    ],
];

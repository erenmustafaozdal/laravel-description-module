<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Baum\Node;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DescriptionCategory extends Node
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'description_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'has_description',
        'has_photo',
        'has_link',
        'show_title',
        'show_description',
        'show_photo',
        'show_link',
        'is_multiple_photo',
        'datatable_filter',
        'datatable_tools',
        'datatable_fast_add',
        'datatable_group_action',
        'datatable_detail',
        'description_is_editor',
        'config_propagation',
        'photo_width',
        'photo_height',
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the descriptions of the description category.
     */
    public function descriptions()
    {
        return $this->hasMany('App\Description','category_id');
    }

    /**
     * Get the thumbnails of the description category.
     */
    public function thumbnails()
    {
        return $this->hasMany('App\DescriptionThumbnail','category_id');
    }

    /**
     * Get the extras of the description category.
     */
    public function extras()
    {
        return $this->hasMany('App\DescriptionExtra','category_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Set the has_description attribute.
     *
     * @param boolean $has_description
     * @return string
     */
    public function setHasDescriptionAttribute($has_description)
    {
        $this->attributes['has_description'] = $has_description == 1 || $has_description === 'true' || $has_description === true ? true : false;
    }

    /**
     * Get the has_description attribute.
     *
     * @param boolean $has_description
     * @return string
     */
    public function getHasDescriptionAttribute($has_description)
    {
        return $has_description == 1 ? true : false;
    }

    /**
     * Set the has_photo attribute.
     *
     * @param boolean $has_photo
     * @return string
     */
    public function setHasPhotoAttribute($has_photo)
    {
        $this->attributes['has_photo'] = $has_photo == 1 || $has_photo === 'true' || $has_photo === true ? true : false;
    }

    /**
     * Get the has_photo attribute.
     *
     * @param boolean $has_photo
     * @return string
     */
    public function getHasPhotoAttribute($has_photo)
    {
        return $has_photo == 1 ? true : false;
    }

    /**
     * Set the has_link attribute.
     *
     * @param boolean $has_link
     * @return string
     */
    public function setHasLinkAttribute($has_link)
    {
        $this->attributes['has_link'] = $has_link == 1 || $has_link === 'true' || $has_link === true ? true : false;
    }

    /**
     * Get the has_link attribute.
     *
     * @param boolean $has_link
     * @return string
     */
    public function getHasLinkAttribute($has_link)
    {
        return $has_link == 1 ? true : false;
    }

    /**
     * Set the show_title attribute.
     *
     * @param boolean $show_title
     * @return string
     */
    public function setShowTitleAttribute($show_title)
    {
        $this->attributes['show_title'] = $show_title == 1 || $show_title === 'true' || $show_title === true ? true : false;
    }

    /**
     * Get the show_title attribute.
     *
     * @param boolean $show_title
     * @return string
     */
    public function getShowTitleAttribute($show_title)
    {
        return $show_title == 1 ? true : false;
    }

    /**
     * Set the show_description attribute.
     *
     * @param boolean $show_description
     * @return string
     */
    public function setShowDescriptionAttribute($show_description)
    {
        $this->attributes['show_description'] = $show_description == 1 || $show_description === 'true' || $show_description === true ? true : false;
    }

    /**
     * Get the show_description attribute.
     *
     * @param boolean $show_description
     * @return string
     */
    public function getShowDescriptionAttribute($show_description)
    {
        return $show_description == 1 ? true : false;
    }

    /**
     * Set the show_photo attribute.
     *
     * @param boolean $show_photo
     * @return string
     */
    public function setShowPhotoAttribute($show_photo)
    {
        $this->attributes['show_photo'] = $show_photo == 1 || $show_photo === 'true' || $show_photo === true ? true : false;
    }

    /**
     * Get the show_photo attribute.
     *
     * @param boolean $show_photo
     * @return string
     */
    public function getShowPhotoAttribute($show_photo)
    {
        return $show_photo == 1 ? true : false;
    }

    /**
     * Set the show_link attribute.
     *
     * @param boolean $show_link
     * @return string
     */
    public function setShowLinkAttribute($show_link)
    {
        $this->attributes['show_link'] = $show_link == 1 || $show_link === 'true' || $show_link === true ? true : false;
    }

    /**
     * Get the show_link attribute.
     *
     * @param boolean $show_link
     * @return string
     */
    public function getShowLinkAttribute($show_link)
    {
        return $show_link == 1 ? true : false;
    }

    /**
     * Set the is_multiple_photo attribute.
     *
     * @param boolean $is_multiple_photo
     * @return string
     */
    public function setIsMultiplePhotoAttribute($is_multiple_photo)
    {
        $this->attributes['is_multiple_photo'] = $is_multiple_photo == 1 || $is_multiple_photo === 'true' || $is_multiple_photo === true ? true : false;
    }

    /**
     * Get the is_multiple_photo attribute.
     *
     * @param boolean $is_multiple_photo
     * @return string
     */
    public function getIsMultiplePhotoAttribute($is_multiple_photo)
    {
        return $is_multiple_photo == 1 ? true : false;
    }





    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    /**
     * model boot method
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * model saved method
         *
         * @param $model
         */
        parent::saved(function($model)
        {
            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_references_brands'); // referanslar
            \Cache::forget('home_showcase_news'); // haberler/duyurular
            \Cache::forget('home_showcase_campaigns'); // kampanyalar
            \Cache::forget('home_services'); // hizmetler
            \Cache::forget('home_creative_slider'); // proje

            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('description_categories')->select('description_categories.id')
                ->where('description_categories.id', $category_id)
                ->join('description_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'description_categories.lft')
                        ->on('cat.lft', '<', 'description_categories.rgt');
                })->get();
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['description_categories', $category->id]));
                \Cache::forget(implode('_', ['description_categories', 'descendantsAndSelf', 'withDescriptions', $category->id]));
                \Cache::forget(implode('_', ['category_descriptions', $category->id]));
            }

            foreach($model->descriptions as $description) {
                \Cache::forget(implode('_',['description','rootCategory',$description->id]));
            }
        });

        /**
         * model moved method
         *
         * @param $model
         */
        parent::moved(function($model)
        {
            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_references_brands'); // referanslar
            \Cache::forget('home_showcase_news'); // haberler/duyurular
            \Cache::forget('home_showcase_campaigns'); // kampanyalar
            \Cache::forget('home_services'); // hizmetler
            \Cache::forget('home_creative_slider'); // proje

            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('description_categories')->select('description_categories.id')
                ->where('description_categories.id', $category_id)
                ->join('description_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'description_categories.lft')
                        ->on('cat.lft', '<', 'description_categories.rgt');
                })->get();
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['description_categories', $category->id]));
                \Cache::forget(implode('_', ['description_categories', 'descendantsAndSelf', 'withDescriptions', $category->id]));
                \Cache::forget(implode('_', ['category_descriptions', $category->id]));
            }

            foreach($model->descriptions as $description) {
                \Cache::forget(implode('_',['description','rootCategory',$description->id]));
            }
        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_references_brands'); // referanslar
            \Cache::forget('home_showcase_news'); // haberler/duyurular
            \Cache::forget('home_showcase_campaigns'); // kampanyalar
            \Cache::forget('home_services'); // hizmetler
            \Cache::forget('home_creative_slider'); // proje

            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('description_categories')->select('description_categories.id')
                ->where('description_categories.id', $category_id)
                ->join('description_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'description_categories.lft')
                        ->on('cat.lft', '<', 'description_categories.rgt');
                })->get();
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['description_categories', $category->id]));
                \Cache::forget(implode('_', ['description_categories', 'descendantsAndSelf', 'withDescriptions', $category->id]));
                \Cache::forget(implode('_', ['category_descriptions', $category->id]));
            }

            foreach($model->descriptions as $description) {
                \Cache::forget(implode('_',['description','rootCategory',$description->id]));
            }
        });
    }
}

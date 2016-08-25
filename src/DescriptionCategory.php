<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Baum\Node;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DescriptionCategory extends Node
{
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
    protected $fillable = ['name','has_description','has_photo','has_link','show_title','show_description','show_photo','show_link','is_multiple_photo'];



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    /**
     * set nodes
     *
     * @param $request
     * @param string $type => move|store
     */
    public function setNode(Request $request, $type = 'store')
    {
        if ( ! $request->has('position')) {
            $model = DescriptionCategory::find($request->input('parent'));
            $this->makeChildOf($model);
            return;
        }

        $input = $type === 'store' ? 'parent' : 'related';
        switch($request->input('position')) {
            case 'firstChild':
                $model = DescriptionCategory::find($request->input($input));
                $this->makeFirstChildOf($model);
                break;
            case 'lastChild':
                $model = DescriptionCategory::find($request->input($input));
                $this->makeChildOf($model);
                break;
            case 'before':
                $model = DescriptionCategory::find($request->input('related'));
                $this->moveToLeftOf($model);
                break;
            case 'after':
                $model = DescriptionCategory::find($request->input('related'));
                $this->moveToRightOf($model);
                break;
        }
    }





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





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Get the name attribute.
     *
     * @param  string $name
     * @return string
     */
    public function getNameAttribute($name)
    {
        return ucfirst_tr($name);
    }

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

    /**
     * Get the created_at attribute.
     *
     * @param  $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format(config('laravel-description-module.date_format'));
    }

    /**
     * Get the created_at attribute for humans.
     *
     * @return string
     */
    public function getCreatedAtForHumansAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Get the updated_at attribute.
     *
     * @param  $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format(config('laravel-description-module.date_format'));
    }

    /**
     * Get the updated_at attribute for humans.
     *
     * @return string
     */
    public function getUpdatedAtForHumansAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}

<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DescriptionThumbnail extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'description_category_thumbnails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'slug','photo_width', 'photo_height' ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['descriptionCategory'];
    public $timestamps = false;





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the photo of the description.
     */
    public function descriptionCategory()
    {
        return $this->belongsTo('App\DescriptionCategory');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}

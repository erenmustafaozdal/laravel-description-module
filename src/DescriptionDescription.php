<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Illuminate\Database\Eloquent\Model;

class DescriptionDescription extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'description_descriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'description' ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['description'];
    public $timestamps = false;





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the description of the description.
     */
    public function description()
    {
        return $this->belongsTo('App\Description');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}

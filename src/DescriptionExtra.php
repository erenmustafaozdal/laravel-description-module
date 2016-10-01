<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DescriptionExtra extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'description_category_columns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','type' ];

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
     * Get the description category of the column.
     */
    public function descriptionCategory()
    {
        return $this->belongsTo('App\DescriptionCategory');
    }

    /**
     * Get the descriptions of the description extra columns.
     */
    public function descriptions()
    {
        return $this->belongsToMany('App\Description','description_description_category_column','column_id','description_id')
            ->withPivot('value');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}

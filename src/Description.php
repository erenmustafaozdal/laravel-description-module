<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;
use Illuminate\Support\Facades\Request;

class Description extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'descriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'is_publish'
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * query filter with id scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        // filter id
        if ($request->has('id')) {
            $query->where('id',$request->get('id'));
        }
        // filter title
        if ($request->has('title')) {
            $query->where('title', 'like', "%{$request->get('title')}%");
        }
        // filter category
        if ($request->has('category')) {
            $query->whereHas('category', function ($query) use($request) {
                $query->where('name', 'like', "%{$request->get('category')}%");
            });
        }
        // filter status
        if ($request->has('status')) {
            $query->where('is_publish',$request->get('status'));
        }
        // filter created_at
        if ($request->has('created_at_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('created_at_from')));
        }
        if ($request->has('created_at_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->get('created_at_to')));
        }
        return $query;
    }

    /**
     * get detail data with all of the relation
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetDetail($query)
    {
        return $query->with([
            'category' => function($query)
            {
                return $query->select(['id','name','has_description','has_photo','has_link']);
            },
            'description' => function($query)
            {
                return $query->select(['id','description_id','description']);
            },
            'photo' => function($query)
            {
                return $query->select(['id','description_id','photo']);
            },
            'multiplePhoto' => function($query)
            {
                return $query->select(['id','description_id','photo']);
            },
            'link' => function($query)
            {
                return $query->select(['id','description_id','link']);
            }
        ]);
    }





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the category of the description.
     */
    public function category()
    {
        return $this->belongsTo('App\DescriptionCategory');
    }

    /**
     * Get the description description.
     */
    public function description()
    {
        return $this->hasOne('App\DescriptionDescription','description_id');
    }

    /**
     * Get the description photo.
     */
    public function photo()
    {
        return $this->hasOne('App\DescriptionPhoto','description_id');
    }

    /**
     * Get the description multiple photo.
     */
    public function multiplePhoto()
    {
        return $this->hasMany('App\DescriptionPhoto','description_id');
    }

    /**
     * Get the description link.
     */
    public function link()
    {
        return $this->hasOne('App\DescriptionLink','description_id');
    }

    /**
     * Get the extra columns of the description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function extras()
    {
        return $this->belongsToMany('App\DescriptionExtra','description_description_category_column','description_id','column_id')
            ->withPivot('value');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * get photo url of the element
     *
     * @return string|null
     */
    public function getPhotoUrlAttribute()
    {
        if (is_null($this->photo)) {
            return "http://placehold.it/{$this->thumbnail_sizes['width']}x{$this->thumbnail_sizes['height']}";
        }
        $config = array_keys(config('laravel-description-module.description.uploads.photo.thumbnails'));
        return $this->photo->getPhoto([], $config[0], true, 'description', 'description_id');
    }

    /**
     * get thumbnail sizes of the element
     *
     * @return array
     */
    public function getThumbnailSizesAttribute()
    {
        return collect(config('laravel-description-module.description.uploads.photo.thumbnails'))->first();
    }

    /**
     * get common title of the element
     *
     * @return string
     */
    public function getCommonTitleAttribute()
    {
        return $this->title_uc_first;
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
            // extra value add
            if (Request::has('extras')) {
                $model->extras()->sync( Request::get('extras') );
            }
        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            $file = new FileRepository(config('laravel-description-module.description.uploads'));
            $file->deleteDirectories($model);
        });
    }
}

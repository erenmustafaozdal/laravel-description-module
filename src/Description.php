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
                return $query->select(['id','parent_id','lft','rgt','depth','name','has_description','has_photo','has_link']);
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
            },
            'extras'
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
        return $this->hasMany('App\DescriptionPhoto','description_id')->orderBy('id','desc');
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

            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_references_brands'); // referanslar
            \Cache::forget('home_showcase_news'); // haberler/duyurular
            \Cache::forget('home_showcase_campaigns'); // kampanyalar
            \Cache::forget('home_services'); // hizmetler
            \Cache::forget('home_creative_slider'); // proje


            $category_id = $model->category->isRoot() ? $model->category_id : $model->category->getRoot()->id;
            $categories = \DB::table('description_categories')->select('description_categories.id','cat.id')
                ->where('description_categories.id', $category_id)
                ->join('description_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'description_categories.lft')
                        ->on('cat.lft', '<', 'description_categories.rgt');
                })->get();
            $ids = array_map(function ($item) {
                return $item->id;
            }, $categories);
            $totalPages = (int) ceil(\DB::table('descriptions')->whereIn('category_id',$ids)->count()/6) + 1;
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['description_categories', 'descendantsAndSelf', 'withDescriptions', $category->id]));
                for($i = 1; $i <= $totalPages; $i++) {
                    \Cache::forget(implode('_', ['category_descriptions',$category->id,'page',$i]));
                }
            }
            \Cache::forget(implode('_',['description','rootCategory',$model->id]));
            \Cache::forget(implode('_',['description',$model->id]));
        });


        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleting(function($model)
        {
            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_references_brands'); // referanslar
            \Cache::forget('home_showcase_news'); // haberler/duyurular
            \Cache::forget('home_showcase_campaigns'); // kampanyalar
            \Cache::forget('home_services'); // hizmetler
            \Cache::forget('home_creative_slider'); // proje


            $category_id = $model->category->isRoot() ? $model->category_id : $model->category->getRoot()->id;
            $categories = \DB::table('description_categories')->select('description_categories.id','cat.id')
                ->where('description_categories.id', $category_id)
                ->join('description_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'description_categories.lft')
                        ->on('cat.lft', '<', 'description_categories.rgt');
                })->get();
            $ids = array_map(function ($item) {
                return $item->id;
            }, $categories);
            $totalPages = (int) ceil(\DB::table('descriptions')->whereIn('category_id',$ids)->count()/6) + 1;
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['description_categories', 'descendantsAndSelf', 'withDescriptions', $category->id]));
                for($i = 1; $i <= $totalPages; $i++) {
                    \Cache::forget(implode('_', ['category_descriptions',$category->id,'page',$i]));
                }
            }
            \Cache::forget(implode('_',['description','rootCategory',$model->id]));
            \Cache::forget(implode('_',['description',$model->id]));
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

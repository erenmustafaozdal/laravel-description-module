<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Description;
use App\DescriptionCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
// events
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\StoreSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\StoreFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\UpdateSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\UpdateFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\DestroySuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\DestroyFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\PublishSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\PublishFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\NotPublishSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\Description\NotPublishFail;
// requests
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\Description\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\Description\ApiUpdateRequest;


class DescriptionApiController extends BaseController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'publish'       => ['route' => 'api.description.publish', 'id' => true],
        'not_publish'   => ['route' => 'api.description.notPublish', 'id' => true],
        'edit_page'     => ['route' => 'admin.description.edit', 'id' => true]
    ];

    /**
     * default realtion urls of the model
     *
     * @var array
     */
    private $relationUrls = [
        'edit_page' => [
            'route'     => 'admin.description_category.description.edit',
            'id'        => 0,
            'model'     => ''
        ],
        'show' => [
            'route'     => 'admin.description_category.description.show',
            'id'        => 0,
            'model'     => ''
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @param integer|null $id
     * @return Datatables
     */
    public function index(Request $request, $id = null)
    {
        // query
        if (is_null($id)) {
            $descriptions = Description::with('category');
        } else {
            $categories = DescriptionCategory::findOrFail($id)->getDescendantsAndSelf()->keyBy('id')->keys();
            $descriptions = Description::whereIn('category_id',$categories)->with('category');
        }
        $descriptions->select(['id','category_id','title','is_publish','created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $descriptions->filter($request);
        }

        // urls
        $addUrls = $this->urls;
        if( ! is_null($id)) {
            $this->relationUrls['edit_page']['id'] = $id;
            $this->relationUrls['edit_page']['model'] = config('laravel-description-module.url.description');
            $this->relationUrls['show']['id'] = $id;
            $this->relationUrls['show']['model'] = config('laravel-description-module.url.description');
            $addUrls = array_merge($addUrls, $this->relationUrls);
        }
        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; },
        ];
        $editColumns = [
            'title'             => function($model) { return $model->title_uc_first; },
            'created_at'        => function($model) { return $model->created_at_table; },
            'category.name'     => function($model) { return $model->category->name_uc_first; },
        ];
        $removeColumns = ['is_publish','category_id'];
        return $this->getDatatables($descriptions, $addColumns, $editColumns, $removeColumns);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function detail($id, Request $request)
    {
        $description = Description::getDetail()
            ->where('id',$id)
            ->select(['id','category_id','title','created_at','updated_at']);

        $editColumns = [
            'size'          => function($model) { return $model->size_table; },
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; },
            'title'         => function($model) { return $model->title_uc_first; },
            'category.name' => function($model) { return $model->category->name_uc_first; },
            'photo.photo'   => function($model)
            {
                // eğer çoklu değilse fotoğraf ise
                if (is_null($model->photo)) {
                    return null;
                }
                if ($model->multiplePhoto->count() === 1) {
                    return $model->photo->getPhoto([], 'normal', true, 'description','description_id');
                }
                // eğer çoklu fotoğraf ise
                return $model->multiplePhoto->map(function($item,$key)
                {
                    return [
                        'photo'     => $item->getPhoto([], 'normal', true, 'description','description_id'),
                        'id'        => $item->id
                    ];
                });
            },
        ];
        $removeColumns = ['multiple_photo'];
        return $this->getDatatables($description, [], $editColumns, $removeColumns);
    }

    /**
     * get model data for edit
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function fastEdit($id, Request $request)
    {
        return Description::with([
            'category' => function($query)
            {
                return $query->select(['id','name']);
            }
        ])->where('id',$id)->first(['id','category_id','title','is_publish']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(Description::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Description $description
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, Description $description)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($description);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Description  $description
     * @return \Illuminate\Http\Response
     */
    public function destroy(Description $description)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($description);
    }

    /**
     * publish model
     *
     * @param Description $description
     * @return \Illuminate\Http\Response
     */
    public function publish(Description $description)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($description, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]);
    }

    /**
     * not publish model
     *
     * @param Description $description
     * @return \Illuminate\Http\Response
     */
    public function notPublish(Description $description)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($description, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * remove photo of the description
     *
     * @param Description $description
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function removePhoto(Description $description, Request $request)
    {
        if ($description->multiplePhoto()->where('id',$request->id)->first()->delete()) {
            return response()->json($this->returnData('success'));
        }
        return response()->json($this->returnData('error'));
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        $this->clearCache();
        if ( $this->groupAlias(Description::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }

    /**
     * clear cache
     *
     * @return void
     */
    private function clearCache()
    {
        \Cache::forget('home_mini_slider');
        \Cache::forget('home_references_brands'); // referanslar
        \Cache::forget('home_showcase_news'); // haberler/duyurular
        \Cache::forget('home_showcase_campaigns'); // kampanyalar
        \Cache::forget('home_services'); // hizmetler
        \Cache::forget('home_creative_slider'); // proje

        $descriptions = collect(\DB::table('descriptions')->get(['id']));
        $totalPages = (int) ceil($descriptions->count()/6) + 1;
        foreach(\DB::table('description_categories')->get(['id']) as $category) {
            \Cache::forget(implode('_', ['description_categories', 'descendantsAndSelf', 'withDescriptions', $category->id]));
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['category_descriptions',$category->id,'page',$i]));
            }
        }
        foreach($descriptions as $description) {
            \Cache::forget(implode('_',['description','rootCategory',$description->id]));
            \Cache::forget(implode('_',['description',$description->id]));
        }
    }
}

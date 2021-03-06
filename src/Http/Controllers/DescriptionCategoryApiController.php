<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DescriptionCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\StoreSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\StoreFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\UpdateSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\UpdateFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\DestroySuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\DestroyFail;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\MoveSuccess;
use ErenMustafaOzdal\LaravelDescriptionModule\Events\DescriptionCategory\MoveFail;
// requests
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\DescriptionCategory\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\DescriptionCategory\ApiUpdateRequest;
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\DescriptionCategory\ApiMoveRequest;
// services
use LMBCollection;


class DescriptionCategoryApiController extends BaseNodeController
{
    /**
     * define values
     *
     * @var array
     */
    private $defineValues = [
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

    /**
     * default relation datas
     *
     * @var array
     */
    private $relations = [
        'thumbnails' => [
            'relation_type'     => 'hasMany',
            'relation'          => 'thumbnails',
            'relation_model'    => '\App\DescriptionThumbnail',
            'datas'             => null
        ],
        'extras' => [
            'relation_type'     => 'hasMany',
            'relation'          => 'extras',
            'relation_model'    => '\App\DescriptionExtra',
            'datas'             => null
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param integer|null $id
     * @return array
     */
    public function index(Request $request, $id = null)
    {
        return $this->getNodes(DescriptionCategory::class, $id);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Request $request)
    {
        $category = DescriptionCategory::find($id);
        if ($category->isRoot()) {
            return $category;
        }
        return $category->getRoot();
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
        if ($request->parent != 0) {
            $category = DescriptionCategory::find($request->parent);
        } else {
            $category = DescriptionCategory::find($request->related);
            $category = $category->isRoot() ? $category : $category->getRoot();
        }
        if ($category->config_propagation) {
            $this->setRelationDefine($category);
        }
        return $this->storeNode(DescriptionCategory::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DescriptionCategory $description_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, DescriptionCategory $description_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $this->updateModel($description_category);

        return [
            'id'        => $description_category->id,
            'name'      => $description_category->name_uc_first
        ];
    }

    /**
     * Move the specified node.
     *
     * @param  ApiMoveRequest $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function move(ApiMoveRequest $request, $id)
    {
        $description_category = DescriptionCategory::findOrFail($id);
        $this->setEvents([
            'success'   => MoveSuccess::class,
            'fail'      => MoveFail::class
        ]);
        $parent = DescriptionCategory::find($request->related);
        $parent = $parent->isRoot() ? $parent : $parent->getRoot();
        if ($parent->config_propagation) {
            $this->setRelationDefine($parent);
        }
        return $this->moveModel($description_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DescriptionCategory  $description_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(DescriptionCategory $description_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($description_category);
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        if($request->has('id')) {
            $description_category = DescriptionCategory::find($request->input('id'));
            $models = $description_category->descendants()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = DescriptionCategory::where('name', 'like', "%{$request->input('query')}%");
        }

        $models = $models->get(['id','parent_id','lft','rgt','depth','name']);
        return LMBCollection::renderAncestorsAndSelf($models, '/',['name_uc_first']);
    }
}

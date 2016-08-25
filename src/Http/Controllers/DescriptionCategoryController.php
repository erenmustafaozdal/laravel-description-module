<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers;

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
// requests
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\DescriptionCategory\StoreRequest;
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\DescriptionCategory\UpdateRequest;

class DescriptionCategoryController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (is_null($id)) {
            return view(config('laravel-description-module.views.description_category.index'));
        }

        $parent_description_category = DescriptionCategory::findOrFail($id);
        return view(config('laravel-description-module.views.description_category.index'), compact('parent_description_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-description-module.views.description_category.create'), compact('operation'));
        }

        $parent_description_category = DescriptionCategory::findOrFail($id);
        return view(config('laravel-description-module.views.description_category.create'), compact('parent_description_category','operation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $id = null)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        if (is_null($id)) {
            $redirect = 'index';
            return $this->storeModel(DescriptionCategory::class,$redirect);
        }
        $redirect = 'description_category.description_category.index';
        $this->setRelationRouteParam($id, config('laravel-description-module.url.description_category'));
        return $this->storeNode(DescriptionCategory::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|DescriptionCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $description_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-description-module.views.description_category.show'), compact('description_category'));
        }

        $parent_description_category = DescriptionCategory::findOrFail($firstId);
        return view(config('laravel-description-module.views.description_category.show'), compact('parent_description_category','description_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|DescriptionCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $description_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-description-module.views.description_category.edit'), compact('description_category','operation'));
        }

        $parent_description_category = DescriptionCategory::findOrFail($firstId);
        return view(config('laravel-description-module.views.description_category.edit'), compact('parent_description_category','description_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|DescriptionCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $description_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'description_category.description_category.show';
            $this->setRelationRouteParam($firstId, config('laravel-description-module.url.description_category'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($description_category, $redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|DescriptionCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $description_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'description_category.description_category.index';
            $this->setRelationRouteParam($firstId, config('laravel-description-module.url.description_category'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($description_category, $redirect);
    }
}

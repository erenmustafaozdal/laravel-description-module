<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule\Http\Controllers;

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
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\Description\StoreRequest;
use ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\Description\UpdateRequest;

class DescriptionController extends BaseController
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
            return view(config('laravel-description-module.views.description.index'));
        }

        $description_category = DescriptionCategory::findOrFail($id);
        return view(config('laravel-description-module.views.description.index'), compact('description_category'));
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
            return view(config('laravel-description-module.views.description.create'), compact('operation'));
        }

        $description_category = DescriptionCategory::findOrFail($id);
        return view(config('laravel-description-module.views.description.create'), compact('description_category','operation'));
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
        if (is_null($id)) {
            $redirect = 'index';
        } else {
            $redirect = 'description_category.description.index';
            $this->setRelationRouteParam($id, config('laravel-description-module.url.description'));
        }

        // description category alınır ve çoklu fotoğraf ilişkisi mi veya değil mi belirlenir
        $description = DescriptionCategory::findOrFail($request->category_id);
        $config = $description->is_multiple_photo ? 'multiple_photo' : 'photo';
        if ( $request->has('photo') && ! $request->file('photo') ) {
            $this->setFileOptions([ config('laravel-description-module.description.uploads.' . $config) ]);
            $this->setElfinderToOptions('photo.photo');
        } else if ($request->file('photo')) {
            $this->setFileOptions([ config('laravel-description-module.description.uploads.' . $config) ]);
        }

        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        $this->setOperationRelation([
            [
                'relation_type'     => 'hasOne',
                'relation'          => 'description',
                'relation_model'    => '\App\DescriptionDescription',
                'datas' => [
                    'description'   => $request->has('description') ? $request->description : null
                ]
            ],
            [
                'relation_type'     => 'hasOne',
                'relation'          => 'link',
                'relation_model'    => '\App\DescriptionLink',
                'datas' => [
                    'link'          => $request->has('link') ? $request->link : null
                ]
            ]
        ]);
        return $this->storeModel(Description::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|Description $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $description = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-description-module.views.description.show'), compact('description'));
        }

        $description_category = DescriptionCategory::findOrFail($firstId);
        return view(config('laravel-description-module.views.description.show'), compact('description', 'description_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|Description $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $description = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-description-module.views.description.edit'), compact('description','operation'));
        }

        $description_category = DescriptionCategory::findOrFail($firstId);
        return view(config('laravel-description-module.views.description.edit'), compact('description', 'description_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|Description $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $description = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'description_category.description.show';
            $this->setRelationRouteParam($firstId, config('laravel-description-module.url.description'));
        }

        if ( $request->has('photo') && ! $request->file('photo') ) {
            $this->setFileOptions([config('laravel-description-module.description.uploads.photo')]);
            $this->setElfinderToOptions('photo.photo');
        }
        if ( $request->has('description')) {
            $this->setOperationRelation([
                [
                    'relation_type' => 'hasOne',
                    'relation' => 'description',
                    'relation_model' => '\App\DescriptionDescription',
                    'datas' => [
                        'description' => $request->has('description') ? $request->description : null
                    ]
                ]
            ]);
        }
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($description,$redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|Description $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $description = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'description_category.description.index';
            $this->setRelationRouteParam($firstId, config('laravel-description-module.url.description'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($description,$redirect);
    }

    /**
     * publish model
     *
     * @param integer|Description $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function publish($firstId, $secondId = null)
    {
        $description = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'description_category.description.show';
            $this->setRelationRouteParam($firstId, config('laravel-description-module.url.description'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($description, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],$redirect);
    }

    /**
     * not publish model
     *
     * @param integer|Description $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function notPublish($firstId, $secondId = null)
    {
        $description = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'description_category.description.show';
            $this->setRelationRouteParam($firstId, config('laravel-description-module.url.description'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($description, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],$redirect);
    }
}

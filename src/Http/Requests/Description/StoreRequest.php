<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\Description;

use App\Http\Requests\Request;
use Sentinel;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('admin.description.store')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max_photo = config('laravel-description-module.description.uploads.photo.max_size');
        $mimes_photo = config('laravel-description-module.description.uploads.photo.mimes');
        $photoValidation = $this->has('photo') || $this->file('photo')
            ? "max:{$max_photo}|image|mimes:{$mimes_photo}"
            : "";

        $rules = [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255',
            'link'              => 'url'
        ];

        for($i = 0; $i < count($this->file('photo')); $i++) {
            $rules['photo.' . $i] = $photoValidation;
        }

        return $rules;
    }
}

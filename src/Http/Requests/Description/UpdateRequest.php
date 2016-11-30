<?php

namespace ErenMustafaOzdal\LaravelDescriptionModule\Http\Requests\Description;

use App\Http\Requests\Request;
use Sentinel;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $hackedRoute = 'admin.description.update';
        if ( ! is_null($this->segment(4))) {
            $hackedRoute = 'admin.description_category.description.update#####' .$this->segment(3);
        }
        return hasPermission($hackedRoute);
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

        $rules = [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255',
        ];

        // Kampanya ve eğitim faaliyeti için zorunlu alan => tarih
        if (in_array($this->segment(3), [4,5])) { // 4: kampanyalar, 5: eğitim faaliyetleri
            $rules['extras.1.value'] = 'required|date';
        }

        // photo elfinder mi
        if ($this->has('photo') && is_string($this->photo)) {
            $rules['photo'] = "elfinder_max:{$max_photo}|elfinder:{$mimes_photo}";
        } else  if (is_array($this->photo)){
            $rules['photo'] = 'array|max:' . config('laravel-description-module.description.uploads.multiple_photo.max_file');
            for($i = 0; $i < count($this->file('photo')); $i++) {
                $rules['photo.' . $i] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
            }
        } else {
            $rules['photo'] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
        }

        return $rules;
    }

    /**
     * get message of the rules
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        // Kampanya ve eğitim faaliyeti için zorunlu alan => tarih
        if ($this->segment(3) == 4) { // 4: kampanyalar
            $messages['extras.1.value.required'] = 'Kampanya tarihi alanı gereklidir.';
            $messages['extras.1.value.date'] = 'Kampanya tarihi alanı geçerli bir tarih olmalıdır.';
        }
        if ($this->segment(3) == 5) { // 5: eğitim faaliyetleri
            $messages['extras.1.value.required'] = 'Eğitim tarihi alanı gereklidir.';
            $messages['extras.1.value.date'] = 'Eğitim tarihi alanı geçerli bir tarih olmalıdır.';
        }
        return $messages;
    }
}

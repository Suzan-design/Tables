<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderRequest extends FormRequest
{

    public function rules()
    {
        $rules = [
            'user_id' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'bio_ar' => 'required|string|max:1000',
            'services' => 'required|string|max:1000',
            'services_ar' => 'required|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'location_work_governorate' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'address_ar' => 'required|string|max:255',
            'start_work' => 'nullable|date_format:H:i',
            'end_work' => 'nullable|date_format:H:i|after:start_work',
            'description' => 'required|string|max:1000',
            'description_ar' => 'required|string|max:1000',
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        if($this->request->get('albums')) {
            foreach($this->request->get('albums') as $key => $val) {
                $rules['albums.'.$key.'.name'] = 'required|string|max:255';
                $rules['albums.'.$key.'.images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
                $rules['albums.'.$key.'.videos.*'] = 'mimes:mp4,mov,avi,flv|max:20480'; // Example for video validation
            }
        }

        return $rules;
    }

}

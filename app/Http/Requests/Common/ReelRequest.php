<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class ReelRequest extends FormRequest
{

    public function rules()
    {
        return [
            'type' => 'required|in:user,venue,event',
            'user_id' => 'required_if:type,user|nullable|exists:users,id',
            'venue_id' => 'required_if:type,venue|nullable|exists:venues,id',
            'event_id' => 'required_if:type,event|nullable|exists:events,id',
            'images.*' => 'image|mimes:jpg,jpeg,png,bmp|max:2048', // 2MB Max per image
            'videos.*' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:20000', // 20MB Max per video
            'description' => 'required|string|max:1000',
            'description_ar' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'type.required' => 'The type field is required.',
        ];
    }
}

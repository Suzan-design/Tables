<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class InterestRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'title_ar' =>  'required|string|max:255',
            'icon'=>'required|image'
        ];
    }
}

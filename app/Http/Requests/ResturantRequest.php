<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'password'=>'required',
            'description'=>'required',
            'Activation_start'=>'required',
            'Activation_end'=>'required',
            'phone_number'=>'required',
            'images'=>'required',
        ];
    }
}

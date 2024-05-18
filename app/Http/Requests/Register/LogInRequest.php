<?php

namespace App\Http\Requests\Register;

use App\Http\Requests\ValidationFormRequest;

class LogInRequest extends ValidationFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'required ',
            'password' => 'required|string|min:8|max:34',
        ];
    }




}

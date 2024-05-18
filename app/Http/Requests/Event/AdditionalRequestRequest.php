<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\ValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class AdditionalRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'class_id' => 'required|exists:event_classes,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ];
    }
}

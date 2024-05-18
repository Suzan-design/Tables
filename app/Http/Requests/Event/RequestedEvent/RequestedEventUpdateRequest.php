<?php

namespace App\Http\Requests\Event\RequestedEvent;

use App\Http\Requests\ValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class RequestedEventUpdateRequest extends FormRequest
{

    public function all($keys = null)
    {
        $data = parent::all($keys);
        return array_intersect_key($data, array_flip([
            'status'
        ]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:Approved,In Progress,Pending',
        ];
    }
}

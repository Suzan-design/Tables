<?php

namespace App\Http\Requests\Event\RequestedEvent;

use App\Http\Requests\ValidationFormRequest;

class RequestedEventRequest extends ValidationFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'date' => 'required|date',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
            'venue_id' => 'nullable|exists:venues,id',
            'service_provider_id' => 'required|array',
            'service_provider_id.*' => 'exists:service_providers,id',
            'additional_notes' => 'nullable|string',
        ];
    }
}

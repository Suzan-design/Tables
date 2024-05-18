<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:events_categories,id',
            'venue_id' => 'required|exists:venues,id',
            'capacity' => 'required|integer|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:2000',
            'interest' => 'nullable|array',
            'interest.*.price' => 'required_with:interest.*|numeric|min:0',
            'interest.*.description' => 'nullable|string|max:1000',
            'classes' => 'nullable|array',
            'classes.*.code' => 'required_with:classes.*|string|max:255',
            'classes.*.ticket_number' => 'required_with:classes.*|numeric|min:1',
            'classes.*.ticket_price' => 'required_with:classes.*|numeric|min:0',
            'classes.*.interest_ids' => 'nullable|array',
            'classes.*.interest_ids.*' => 'exists:interest,id',
            'service_providers' => 'nullable|array',
            'service_providers.*' => 'exists:service_providers,id',
            'event_trips' => 'nullable|array',
            'event_trips.*.start_date' => 'required_with:event_trips.*|date|after_or_equal:today',
            'event_trips.*.end_date' => 'required_with:event_trips.*|date|after_or_equal:event_trips.*.start_date',
            'event_trips.*.description' => 'nullable|string|max:1000'
        ];
    }
}

<?php

namespace App\Http\Requests\Action;

use App\Http\Requests\ValidationFormRequest;

class ReviewRequest extends ValidationFormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:255'
        ];
    }
}

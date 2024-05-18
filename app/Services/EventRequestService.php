<?php

namespace App\Services;

use App\Models\Event\EventRequest;

class EventRequestService
{
    public function getAllEventRequests()
    {
        return EventRequest::all();
    }

    public function updateEventRequest(EventRequest $eventRequest, $data)
    {
        $eventRequest->update($data);
        return $eventRequest;
    }

    public function deleteEventRequest(EventRequest $eventRequest)
    {
        $eventRequest->delete();
    }
}

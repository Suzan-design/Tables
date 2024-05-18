<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RequestedEvent\RequestedEventUpdateRequest;
use App\Models\Event\EventRequest;
use App\Services\EventRequestService;

class EventRequestController extends Controller
{
    protected $eventRequestService;

    public function __construct(EventRequestService $eventRequestService)
    {
        $this->eventRequestService = $eventRequestService;
    }

    public function index()
    {
        $event_requests = $this->eventRequestService->getAllEventRequests();
        return view('event_request.index', compact('event_requests'));
    }

    public function show(EventRequest $event_request)
    {
        return view('event_request.show', compact('event_request'));
    }

    public function update(RequestedEventUpdateRequest $request, EventRequest $event_request)
    {
        $this->eventRequestService->updateEventRequest($event_request, $request->all());
        return redirect()->route('event-requests.index');
    }

    public function destroy(EventRequest $event_request)
    {
        $this->eventRequestService->deleteEventRequest($event_request);
        return redirect()->route('event-requests.index')->with('success', 'EventRequest deleted successfully.');
    }
}

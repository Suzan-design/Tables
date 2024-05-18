<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventRequest;
use App\Models\Event\Event;
use App\Models\User\MobileUser;
use App\Models\User\Organizer;
use App\Services\EventService;
use App\Models\Common\Interest;
use App\Models\Event\EventsCategory;
use App\Models\ServiceProvider\ServiceProvider;
use App\Models\Venue\Venue;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index()
    {
        $events = $this->eventService->getAllEvents();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        // Fetch necessary data for the create view
        $venues = Venue::all();
        $categories = EventsCategory::select('id', 'title')->get();
        $interests = Interest::select('id', 'title')->get();
        $serviceProviders = ServiceProvider::select('id', 'user_id')->where('type' , 'Approved')->get();
        $organizers = Organizer::select('id', 'name')->where('type' , 'Approved')->get();
        return view('events.create', compact('venues', 'categories', 'interests', 'serviceProviders' , 'organizers'));
    }

    public function store(EventRequest $request)
    {
        $request['type'] = $request->has('type') ? 'featured' : 'normal';
        $this->eventService->createEvent($request->all(), $request->file('images'), $request->file('videos'));
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show($eventId)
    {
        $event = Event::withoutGlobalScopes()->findOrFail($eventId);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $venues = Venue::all();
        $categories = EventsCategory::select('id', 'title')->get();
        $interests = Interest::select('id', 'title')->get();
        $serviceProviders = ServiceProvider::select('id', 'user_id')->where('type' , 'Approved')->get();
        $organizers = Organizer::select('id', 'name')->where('type' , 'Approved')->get();

        return view('events.edit', compact('event','venues', 'categories', 'interests', 'serviceProviders' , 'organizers'));
    }

    public function update(EventRequest $request, Event $event)
    {
        $this->eventService->updateEvent($event, $request);
        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->eventService->deleteEvent($event);
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Common\Interest;
use App\Models\Event\EventsCategory;
use App\Models\User\MobileUser;
use App\Services\api\EventService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function category()
    {
        return response()->json([
            'status' => true,
            'category' => EventsCategory::select('id','icon' , 'title','title_ar')->get() ,
        ]);
    }

    public function organizer(){
        $topOrganizers = MobileUser::has('organizerInfo')->with('organizerInfo')->withCount('followers')
            ->where('type', 'organizer')
            ->orderByDesc('followers_count')
            ->paginate(4);

        return response()->json([
            'status'  => true  ,
            'organizers' => $topOrganizers
        ]);
    }

    public function featured_event()
    {
        return response()->json([
            'status' => true,
            'featured_event' => $this->eventService->getFeaturedEvents(),
        ]);
    }

    public function trending_event()
    {
        return response()->json([
            'status' => true,
            'trending_event' => $this->eventService->getTrendingEvents(),
        ]);
    }

    public function organizer_event()
    {
        return response()->json([
            'status' => true,
            'organizer_event' => $this->eventService->getOrganizerEvent(),
        ]);
    }

    public function eventsInUserCity()
    {
        return response()->json([
            'status' => true,
            'events_in_your_city' => $this->eventService->getEventsInUserCity(),
        ]);
    }

    public function getJustForYouEvents()
    {
        return response()->json([
            'status' => true,
            'just_for_you' => $this->eventService->getJustForYouEvents()
        ]);
    }

    public function toNightEvent()
    {
        return response()->json([
            'status' => true,
            'toNightEvent' => $this->eventService->getTonightEvents()
        ]);
    }

    public function OfferEvent()
    {
        return response()->json([
            'status' => true,
            'OfferEvent' => $this->eventService->getOfferEvents()
        ]);
    }

    public function thisWeekEvent()
    {
        return response()->json([
            'status' => true,
            'thisWeekEvent' => $this->eventService->getThisWeekEvents()
        ]);
    }
    public function eventAccordingCategory($id)
    {
        return response()->json([
            'status' => true,
            'Events' => $this->eventService->getEventAccordingCategory($id)
        ]);
    }

}

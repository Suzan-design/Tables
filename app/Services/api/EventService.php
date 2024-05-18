<?php

namespace App\Services\api;

use App\Models\Action\Follow;
use App\Models\Event\Event;
use App\Models\User\MobileUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class EventService
{


    public function NearestEvents($data)
    {
        return Event::nearest($data['latitude'], $data['longitude'], $data['distance'])->get();
    }

    public function getOfferEvents()
    {
        return Event::with([
            'venue' => function($query) {
                $query->select('id', 'governorate', 'latitude', 'longitude');
            },
            'offer' // Add this line to also load the offer relationship
        ])
            ->has('offer') // Ensure only events with an offer are returned
            ->select('id', 'title', 'start_date', 'ticket_price', 'videos',
                'images', 'venue_id')
            ->paginate(4);
    }

    public function event_details($id)
    {
        $event = Event::with([
            'eventTrips:id,event_id,start_date,end_date,description',
            'venue:id,governorate,latitude,longitude,name',
            'organizer:id',
            'organizer.organizerInfo',
            'categoriesEvents',
            'interests',
            'serviceProviders',
            'classes' => function($query) {
                $query->with('interests');
            }
        ])->find($id);

        if ($event) {
            // Retrieve category IDs of the current event
            $categoryIds = $event->categoriesEvents->pluck('id');

            // Fetch 5 events with at least one common category, excluding the current event
            $relatedEvents = Event::where('id', '!=', $id)
                ->whereHas('categoriesEvents', function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds);
                })
                ->take(5)
                ->get();

            // Extracting unique users from bookings
            $uniqueUsers = $event->bookings()
                ->with(['user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name' , 'type');
                }])
                ->get()
                ->pluck('user')
                ->unique('id')
                ->values();

            $event->bookings = $uniqueUsers;

            return response()->json([
                'status'  => true,
                'event' => $event ,
                'relatedEvents' => $relatedEvents
            ]);
        }

        return response()->json([
            'status' => false,
            'message'=> 'Not Found'
        ], 404);
    }

    public function getFeaturedEvents()
    {
        return Event::with(['venue' => function($query) {
            $query->select('id', 'governorate', 'latitude', 'longitude');
        }])->where('type', 'featured')
            ->select('id' , 'title', 'start_date', 'ticket_price','videos',
            'images' , 'venue_id')
            ->paginate(4);
    }

    public function getTrendingEvents()
    {
        return Event::with(['venue' => function($query) {
            $query->select('id', 'governorate', 'latitude', 'longitude');
        }])->select('id', 'title','title_ar', 'start_date', 'ticket_price', 'videos',
            'images' ,  'venue_id')
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->paginate(4);
    }

    public function getOrganizerEvent()
    {
        $followedOrganizers = Follow::where('user_id' , Auth::id())->get() ;

        return Event::with(['venue' => function($query) {
            $query->select('id', 'governorate', 'latitude', 'longitude');
        }])->whereIn('organizer_id', $followedOrganizers->pluck('organizer_id'))->select('id' ,'title', 'start_date', 'ticket_price', 'videos',
            'images' , 'venue_id')->paginate(4);
    }

    public function getEventsInUserCity()
    {
        $userState = Auth::guard('mobile')->user()->state;

        return Event::with(['venue' => function($query) use ($userState) {
            $query->where('governorate', $userState)
                ->select('id', 'governorate', 'latitude', 'longitude');
        }])->whereHas('venue', function ($query) use ($userState) {
            $query->where('governorate', $userState);
        })->select('id' ,'title', 'title_ar','start_date', 'ticket_price', 'videos',
            'images' , 'venue_id')->paginate(4);
    }

    public function getJustForYouEvents()
    {
        $authenticatedUser = Auth::guard('mobile')->user();

        if ($authenticatedUser) {
            // Retrieve the event_category_ids associated with the authenticated user
            $userInterestIds = $authenticatedUser->eventCategories()->pluck('events_categories.id')->toArray();

            // Use the retrieved event_category_ids in your existing code
            $events = Event::with(['venue' => function($query) {
                $query->select('id', 'governorate', 'latitude', 'longitude');
            }])->whereHas('categoriesEvents', function ($query) use ($userInterestIds) {
                $query->whereIn('category_id', $userInterestIds);
            })->select('id' ,'title', 'title_ar', 'start_date', 'ticket_price', 'videos',
                'images' , 'venue_id')->paginate(4);
            return $events;
        }
    }

    public function getTonightEvents()
    {
        $startTime = Carbon::today()->setHour(18);
        $endTime = Carbon::today()->endOfDay();

        return Event::with(['venue' => function($query) {
            $query->select('id', 'governorate', 'latitude', 'longitude');
        }])->where('start_date', '>=', $startTime)
            ->where('start_date', '<=', $endTime)
            ->select('id' ,'title','title_ar', 'start_date', 'ticket_price' , 'videos',
                'images' , 'venue_id' )
            ->paginate(4);
    }

    public function getThisWeekEvents()
    {
        $today = Carbon::today();
        $sevenDaysLater = Carbon::today()->addDays(7);

        return Event::with(['venue' => function($query) {
            $query->select('id', 'governorate', 'latitude', 'longitude');
        }])->where('start_date', '>=', $today)
            ->where('start_date', '<=', $sevenDaysLater)
            ->select('id' ,'title','title_ar', 'start_date', 'ticket_price' , 'videos',
                'images' , 'venue_id' )
            ->paginate(4);
    }

    public function getEventAccordingCategory($id)
    {
        return Event::with(['venue' => function($query) {
            $query->select('id', 'governorate', 'latitude', 'longitude');
        }])->whereHas('categoriesEvents', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->select('id' ,'title','title_ar', 'start_date', 'ticket_price','videos',
            'images' , 'venue_id' )->paginate(4);
    }

}

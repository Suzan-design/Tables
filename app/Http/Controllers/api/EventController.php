<?php

namespace App\Http\Controllers\api;

use App\Events\NotificationEvent;
use App\Models\Action\Notification;
use App\Models\Event\Event;
use App\Models\User\MobileUser;
use App\Services\api\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    protected $eventService ;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService ;
    }


    public function show($id)
    {
        return $this->eventService->event_details($id) ;
    }

    public function showGoing($id)
    {
        $event = Event::find($id) ;

        $uniqueUserIds = $event->bookings()
            ->select('user_id')
            ->distinct()
            ->pluck('user_id');

        $uniqueUsers = MobileUser::whereIn('id', $uniqueUserIds)
            ->select('id', 'first_name', 'last_name', 'type' , 'image')
            ->paginate(10);

        return response()->json([
           'status' =>  true  ,
           'Goings' =>  $uniqueUsers
        ]);
    }

    public function showNearestEvents(Request $request)
    {
        return response()->json([
            'status'=> true ,
            'events' => $this->eventService->NearestEvents($request->all())
        ]);
    }

    public function filter(Request $request)
    {
        $events= Event::query() ;

        if ($request['event_category'] != null) {
            $eventsCategory = $request['event_category'] ;
            $events = $events->whereHas('categoriesEvents', function ($query) use ($eventsCategory) {
                $query->whereIn('title', $eventsCategory);
            });
        }

        if ($request['state'] != null) {
            $state = $request['state'] ;
            $events = $events->whereHas('venue', function ($query) use ($state) {
                $query->where('governorate', $state);
            });
        }

        if ($request['min_ticket_price'] != null){
            $events = $events->where('ticket_price' , '>' , $request['min_ticket_price']);
        }

        if ($request['max_ticket_price'] != null){
            $events = $events->where('ticket_price' , '<' , $request['max_ticket_price']);
        }
        if($request['distance']){
            $events = $events->nearest($request['latitude'], $request['longitude'], $request['distance']) ;
        }

        return response()->json([
            'status' => true ,
            'events' => $events->get()
        ]);

    }

    public function favorite_filter(Request $request)
    {
        $events= Event::query() ;
        $userId = auth()->id();

        $events = $events->whereHas('eventFollows', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        if ($request['event_category'] != null) {
            $eventsCategory = $request['event_category'] ;
            $events = $events->whereHas('categoriesEvents', function ($query) use ($eventsCategory) {
                $query->whereIn('title', $eventsCategory);
            });
        }

        if ($request['state'] != null) {
            $state = $request['state'] ;
            $events = $events->whereHas('venue', function ($query) use ($state) {
                $query->where('governorate', $state);
            });
        }

        if ($request['min_ticket_price'] != null){
            $events = $events->where('ticket_price' , '>' , $request['min_ticket_price']);
        }

        if ($request['max_ticket_price'] != null){
            $events = $events->where('ticket_price' , '<' , $request['max_ticket_price']);
        }

        return response()->json([
            'status' => true ,
            'events' => $events->get()
        ]);

    }
    public function search(Request $request)
    {
        $result = Event::where('title' , 'like', '%' . $request['Search'] . '%')->orWhere('title_ar' , 'like', '%' . $request['Search'] . '%')
            ->orWhere('description' , 'like', '%' . $request['Search'] . '%')->orWhere('description_ar' , 'like', '%' . $request['Search'] . '%')
            ->select('id','images', 'title' , 'title_ar' , 'start_date', 'end_date')->get();

        return response()->json([
            'status' => true ,
            'result' => $result
        ])   ;
    }


    public function invite(Request $request)
    {
        event(new NotificationEvent( 'You have a new event Invitation ' ,Auth::user()->first_name .' '. Auth::user()->last_name . ' Invite you to event ' . $request['event_name'] .' '. $request['event_id'] , $request['user_id']));
        Notification::create([
            'title' =>'You have a new event Invitation ' ,
            'description' => Auth::user()->first_name .' '. Auth::user()->last_name . ' Invite you to event ' . $request['event_name'] .' '. $request['event_id'],
            'user_id' => $request['user_id']
        ]);

        return response()->json([
            'status' => true ,
            'message' => 'Invited Successfully'
        ]) ;
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Event\RequestedEvent\RequestedEventRequest;
use App\Models\Event\EventRequest;
use App\Traits\FileStorageTrait;
use Illuminate\Support\Facades\Auth;

class EventRequestController extends Controller
{
    use FileStorageTrait ;

    public function store(RequestedEventRequest $request)
    {
        $imagePaths = [];
        if ($request->has('images')) {
            $imagePaths = $this->handleFiles($request['images'] , 'RequestedEventFolder') ;
        }

        $eventRequest = new EventRequest([
            'user_id' => Auth::guard('mobile')->id() ,
            'title' => $request['title'],
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'date' => $request->date,
            'adults' => $request->adults ,
            'child' => $request->child ,
            'images' => json_encode($imagePaths),
            'description' => $request->description,
            'venue_id' => $request->venue_id,
            'service_provider_id' => json_encode($request->service_provider_id),
            'additional_notes' => $request->additional_notes,
            'status' => 'pending',
            'category_id' => $request->category_id ,
            'start_time' => $request->start_time ,
            'end_time' => $request->end_time ,
        ]);

        $eventRequest->save();

        return response()->json([ 'status' => true, 'message' => 'Event request created successfully', 'data' => $eventRequest], 200);
    }

    public function my_request()
    {
        $userId = Auth::guard('mobile')->id();

        // Fetch all event requests for the authenticated user
        $eventRequests = EventRequest::with(['venue:id,name,name_ar' , 'category:id,title'])->where('user_id', $userId)->get();

        $eventRequestsWithProviders = $eventRequests->map(function ($eventRequest) {
            // For each event request, get associated service providers
            $serviceProviders = $eventRequest->serviceProviders();

            // Add the service providers to the event request
            $eventRequest->service_providers = $serviceProviders;

            return $eventRequest;
        });

        // Return response with all event requests and their service providers
        return response()->json([
            'status' => true,
            'message' => 'Event requests with service providers',
            'data' => $eventRequestsWithProviders
        ], 200);
    }


}

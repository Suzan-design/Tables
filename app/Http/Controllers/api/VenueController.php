<?php

namespace App\Http\Controllers\api;


use App\Models\Venue\Venue;

class VenueController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true ,
            'message' => Venue::with('albums')->paginate(4)
        ]) ;
    }

    public function show($id)
    {
        $venue = Venue::with('albums')->find($id) ;
        return response()->json([
           'status' => true ,
           'venue' => $venue
        ]);
    }

}

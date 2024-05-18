<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function index()
    {
        $offers = Offer::with(['event' => function ($query) {
            $query->withoutGlobalScopes(); // This removes all global scopes for the event relationship
        }])->get();

        return view('offer.index', compact('offers'));
    }

    public function create()
    {
        return view('offer.create');
    }

    public function store(Request $request)
    {
        $offer = Offer::create([
            'event_id' => $request['event_id'] ,
            'percent' => $request['percent']
        ]);
        return redirect()->route('events-offers.index')->with('success', 'Offer created successfully.');
    }
}

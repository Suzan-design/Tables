<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventsCategory;
use App\Models\PromoCode\EventPromoCode;
use App\Models\PromoCode\PromoCode;
use App\Models\PromoCode\UserPromoCode;
use App\Models\User\MobileUser;
use App\Traits\FileStorageTrait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PromoCodeController extends Controller
{
    use FileStorageTrait;

    public function index()
    {
        $promo_codes = PromoCode::all() ;
        return view('promo_code.index', compact('promo_codes'));
    }


    public function create()
    {
        $categories = EventsCategory::all() ;
        return view('promo_code.create' , compact('categories'));
    }


    public function store(Request $request)
    {
        $path = $this->storefile($request->file('image'), 'PromoCodeImages');

        $promo_code = PromoCode::create([
            'title' => $request['title'] ,
            'description' => $request['description'],
            'image' =>$path,
            'code' =>$request['code'],
            'discount' =>$request['discount'],
            'limit' =>$request['limit'],
            'start-date' =>$request['start-date'],
            'end-date' =>$request['end-date'],
        ]);
        $events = Event::query();

        $events->when(isset($request['events_id']), function ($query) use ($request) {
            return $query->whereIn('id', $request['events_id']);
        }, function ($query) use ($request) {
            // If 'events_id' is not set or null, apply other conditions
            $query->when(isset($request['event_category_ids']) && !empty($request['event_category_ids']), function ($q) use ($request) {
                $q->whereHas('categoriesEvents', function ($q) use ($request) {
                    $q->whereIn('category_id', $request['event_category_ids']);
                });
            });
            $query->when(isset($request['event_city']) && !empty($request['event_city']), function ($q) use ($request) {
                $q->whereHas('venue', function ($q) use ($request) {
                    $q->whereIn('governorate', $request['event_city']);
                });
            });
        });

        // Apply limit if provided
        $events->when(isset($request['event_limit']), function ($query) use ($request) {
            return $query->take($request['event_limit']);
        });

        $eventIds = $events->pluck('id');

        $mobileUserIds = MobileUser::query()
            ->when(isset($request->user_interest_ids) && !empty($request->user_interest_ids), function ($query) use ($request) {
                $query->whereHas('eventCategories', function ($q) use ($request) {
                    $q->whereIn('events_category_id', $request->user_interest_ids);
                });
            })
            ->when(isset($request->ageRangeStart), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$request->ageRangeStart]);
            })
            ->when(isset($request->ageRangeEnd), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$request->ageRangeEnd]);
            })
            ->withCount(['bookings'])
            ->when(isset($request->bookingRangeStart) && isset($request->bookingRangeEnd), function ($query) use ($request) {
                $query->havingRaw('bookings_count >= ? AND bookings_count <= ?', [$request->bookingRangeStart, $request->bookingRangeEnd]);
            })
            ->when(isset($request->user_city) && !empty($request->user_city), function ($query) use ($request) {
                $query->whereIn('state', $request->user_city);
            })
            ->when(isset($request['user_limit']), function ($query) use ($request) {
                return $query->take($request['user_limit']);
            })
            ->pluck('id');

        foreach ($eventIds as $eventId) {
            EventPromoCode::create([
               'event_id' => $eventId ,
               'promo_code_id' => $promo_code->id
            ]);
        }
        foreach ($mobileUserIds as $mobileUserId) {
            UserPromoCode::create([
                'user_id' => $mobileUserId ,
                'promo_code_id' => $promo_code->id
            ]);
        }

        return redirect()->route('promo_code.index')->with('success', 'Promo Code created successfully.');
    }


    public function show(PromoCode $promo_code)
    {
        return view('promo_code.show', compact('promo_code'));
    }


    public function edit(PromoCode $promo_code)
    {
        return view('promo_code.edit', compact('promo_code'));
    }


    public function update(Request $request, PromoCode $promo_code)
    {
        return redirect()->route('promo_code.index')->with('success', 'Promo Code updated successfully.');
    }


    public function destroy(PromoCode $promo_code)
    {
        $promo_code->delete();
        return redirect()->route('promo_code.index')->with('success', 'Promo Code deleted successfully.');
    }

    public function count(Request $request)
    {

        $usersCount = $this->getUsersCount($request);
        $eventsCount = $this->getEventsCount($request);

        return response()->json([
            'user_count' => $usersCount,
            'eventsCount' => $eventsCount
        ]) ;
    }


    protected function getUsersCount(Request $request)
    {
        return MobileUser::query()
            ->when(isset($request->user_interest_ids) && !empty($request->user_interest_ids), function ($query) use ($request) {
                $query->whereHas('eventCategories', function ($q) use ($request) {
                    $q->whereIn('events_category_id', $request->user_interest_ids);
                });
            })
            ->when(isset($request->ageRangeStart), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$request->ageRangeStart]);
            })
            ->when(isset($request->ageRangeEnd), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$request->ageRangeEnd]);
            })
            ->withCount(['bookings'])
            ->when(isset($request->bookingRangeStart) && isset($request->bookingRangeEnd), function ($query) use ($request) {
                $query->havingRaw('bookings_count >= ? AND bookings_count <= ?', [$request->bookingRangeStart, $request->bookingRangeEnd]);
            })
            ->when(isset($request->user_city) && !empty($request->user_city), function ($query) use ($request) {
                $query->whereIn('state', $request->user_city);
            })
            ->when(isset($request['user_limit']), function ($query) use ($request) {
                return $query->take($request['user_limit']);
            })
            ->count() ;
    }

    protected function getEventsCount(Request $request)
    {
        $events = Event::query();

        $events->when(isset($request['events_id']), function ($query) use ($request) {
            return $query->whereIn('id', $request['events_id']);
        }, function ($query) use ($request) {
            // If 'events_id' is not set or null, apply other conditions
            $query->when(isset($request['event_category_ids']) && !empty($request['event_category_ids']), function ($q) use ($request) {
                $q->whereHas('categoriesEvents', function ($q) use ($request) {
                    $q->whereIn('category_id', $request['event_category_ids']);
                });
            });
            $query->when(isset($request['event_city']) && !empty($request['event_city']), function ($q) use ($request) {
                $q->whereHas('venue', function ($q) use ($request) {
                    $q->whereIn('governorate', $request['event_city']);
                });
            });
        });

        // Apply limit if provided
        $events->when(isset($request['event_limit']), function ($query) use ($request) {
            return $query->take($request['event_limit']);
        });

        return $events->count();
    }
}

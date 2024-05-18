<?php

namespace App\Models\Event;

use App\Models\Action\EventFollow;
use App\Models\Action\Follow;
use App\Models\Common\Interest;
use App\Models\Common\Reel;
use App\Models\PromoCode\EventPromoCode;
use App\Models\ServiceProvider\ServiceProvider;
use App\Models\User\MobileUser;
use App\Models\Venue\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new \App\Scopes\UpcomingEventScope);

        // Listen for the deleting event
        static::deleting(function ($event) {

            if ($event->images) {
                $images = json_decode($event->images, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            if ($event->videos) {
                $videos = json_decode($event->videos, true);
                if (is_array($videos)) {
                    foreach ($videos as $video) {
                        Storage::disk('public')->delete($video);
                    }
                }
            }
        });
    }


    protected $fillable = [
        'organizer_id' , 'title' , 'title_ar', 'venue_id', 'capacity', 'start_date', 'end_date', 'ticket_price', 'description' , 'description_ar' , 'type' , 'images' , 'videos'
    ];

    protected $appends = ['is_followed_by_auth_user' ];

    public function getIsFollowedByAuthUserAttribute()
    {
        return $this->followers()->where('user_id', Auth::id())->exists();
    }

    public function followers()
    {
        return $this->hasMany(EventFollow::class, 'event_id');
    }

    public function eventClasses()
    {
        return $this->hasMany(EventClass::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, EventClass::class , 'event_id', 'class_id');
    }

    public function organizer()
    {
        return $this->belongsTo(MobileUser::class , 'organizer_id') ;
    }

    public function serviceProviders()
    {
        return $this->belongsToMany(ServiceProvider::class);
    }

    public function eventTrips()
    {
        return $this->hasMany(EventTrip::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }


    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_event', 'event_id', 'interest_id')
            ->withPivot('price', 'description' , 'description_ar') ;
    }

    public function categoriesEvents()
    {
        return $this->belongsToMany(EventsCategory::class, 'categories_events', 'event_id', 'category_id') ;
    }

    public function classes()
    {
        return $this->hasMany(EventClass::class) ;
    }

    public function reels()
    {
        return $this->hasMany(Reel::class);
    }

    public function eventsLikes()
    {
        return $this->hasMany(EventsLike::class);
    }

    public function eventsComments()
    {
        return $this->hasMany(EventsComment::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(EventPromoCode::class);
    }
    public function offer()
    {
        return $this->hasOne(Offer::class , 'event_id');
    }

    public function scopeNearest($query, $lat, $lng, $radius)
    {
        $haversine = "(6371 * acos(cos(radians($lat))
                   * cos(radians(venues.latitude))
                   * cos(radians(venues.longitude)
                   - radians($lng))
                   + sin(radians($lat))
                   * sin(radians(venues.latitude))))";
        return $query->join('venues', 'events.venue_id', '=', 'venues.id')
            ->selectRaw("events.* , {$haversine} AS distance")
            ->havingRaw("distance <= ?", [$radius])
            ->orderBy('distance');
    }


    public function eventFollows ()
    {
        return $this->hasMany(EventFollow::class , 'event_id');
    }
    protected $hidden = [
        'created_at' ,
        'updated_at'
    ] ;
}

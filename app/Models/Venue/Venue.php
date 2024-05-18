<?php

namespace App\Models\Venue;

use App\Models\Action\VenueReview;
use App\Models\Event\Event;
use App\Models\Event\EventRequest;
use App\Models\ServiceProvider\ServiceProvidersAlbums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class  Venue extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function ($object) {
            if ($object->profile) {
                Storage::disk('public')->delete($object->profile);
            }
        });
    }

    protected $fillable = [
        'name', 'name_ar', 'capacity', 'governorate', 'location_description' , 'location_description_ar', 'latitude', 'longitude', 'contact_number' , 'description','description_ar' , 'profile'
    ];

    protected $appends = ['average_rating']; // Ensure the average rating is automatically appended to the model's array and JSON forms.

    public function getAverageRatingAttribute() {
        return $this->reviews()->average('rate'); // This calculates the average rate of the reviews.
    }


    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function albums()
    {
        return $this->hasMany(VenueAlbum::class) ;
    }

    public function eventRequests()
    {
        return $this->hasMany(EventRequest::class);
    }

    public function categoriesVenues()
    {
        return $this->hasMany(CategoriesVenue::class);
    }
    public function reviews()
    {
        return $this->hasMany(VenueReview::class , 'venue_id');
    }

    protected $hidden = [
        'created_at' ,
        'updated_at'
    ] ;
}

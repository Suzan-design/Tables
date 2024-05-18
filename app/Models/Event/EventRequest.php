<?php

namespace App\Models\Event;

use App\Models\ServiceProvider\ServiceProvider;
use App\Models\User\MobileUser;
use App\Models\Venue\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventRequest extends Model
{
    protected $table = 'event_requests'; // Define the table name if it's not the plural of the model name

    protected static function boot()
    {
        parent::boot();

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
        });
    }
    protected $fillable = [
        'title',
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'date',
        'start_time' ,
        'end_time' ,
        'adults',
        'child',
        'images',
        'description',
        'venue_id',
        'service_provider_id',
        'additional_notes',
        'status' ,
        'category_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'images' => 'array',
        'service_provider_id' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(MobileUser::class , 'user_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    public function serviceProviders()
    {
        $serviceProviderIds = json_decode($this->service_provider_id, true);

        // Check if $serviceProviderIds is an array and not empty
        if (is_array($serviceProviderIds) && count($serviceProviderIds) > 0) {
            return ServiceProvider::with(['category:id,title', 'user:id,first_name,last_name'])
                ->whereIn('id', $serviceProviderIds)
                ->get();
        } else {
            // Return an empty collection if $serviceProviderIds is not an array or is empty
            return collect();
        }
    }

    public function category()
    {
        return $this->belongsTo(EventsCategory::class);
    }
}

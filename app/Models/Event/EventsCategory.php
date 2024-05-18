<?php

namespace App\Models\Event;

use App\Models\User\MobileUser;
use App\Models\User\Organizer;
use App\Models\Venue\CategoriesVenue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventsCategory extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function ($object) {
            if ($object->icon) {
                Storage::disk('public')->delete($object->icon);
            }
        });
    }

    protected $fillable = ['title' , 'title_ar', 'icon'];

    public function users()
    {
        return $this->belongsToMany(MobileUser::class , 'event_category_mobile_user');
    }

    public function eventRequests()
    {
        return $this->hasMany(EventRequest::class, 'category_id');
    }

    public function categoriesEvents()
    {
        return $this->hasMany(CategoriesEvent::class, 'category_id');
    }

    public function categoriesVenues()
    {
        return $this->hasMany(CategoriesVenue::class, 'category_id');
    }

    public function organizers()
    {
        return $this->belongsToMany(Organizer::class);
    }
}

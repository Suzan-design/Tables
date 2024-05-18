<?php

namespace App\Models\ServiceProvider;

use App\Models\Action\ServiceProviderReview;
use App\Models\Event\Event;
use App\Models\User\MobileUser;
use App\Scopes\ExcludeAttributeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceProvider extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new ExcludeAttributeScope('type', 'pending'));

        parent::boot();

        static::deleting(function ($object) {
            if ($object->profile) {
                Storage::disk('public')->delete($object->profile);
            }
            if ($object->cover) {
                Storage::disk('public')->delete($object->cover);
            }
        });
    }

    protected $fillable = [
        'user_id','name' ,'name_ar' , 'bio' , 'bio_ar' , 'category_id', 'location_work_governorate', 'address' , 'address_ar', 'start_work', 'end_work','profile','cover' ,'latitude', 'longitude' , 'description' , 'description_ar' ,'services' , 'services_ar' , 'type'
    ];

    protected $appends = ['average_rating']; // Ensure the average rating is automatically appended to the model's array and JSON forms.

    public function getAverageRatingAttribute() {
        return $this->reviews()->average('rate'); // This calculates the average rate of the reviews.
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function albums()
    {
        return $this->hasMany(ServiceProvidersAlbums::class) ;
    }

    public function user()
    {
        return $this->belongsTo(MobileUser::class);
    }

    public function category()
    {
        return $this->belongsTo(ServicesCategory::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(ServiceProviderReview::class , 'service_provider_id') ;
    }

}


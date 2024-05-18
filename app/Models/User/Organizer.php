<?php

namespace App\Models\User;

use App\Models\Action\Follow;
use App\Models\Event\EventsCategory;
use App\Models\Venue\VenueAlbum;
use App\Scopes\ExcludeAttributeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Organizer extends Model
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
        'mobile_user_id',
        'name',
        'bio',
        'services',
        'other_category',
        'state',
        'profile',
        'cover' ,
        'type'
    ];

    protected $appends = ['is_followed_by_auth_user'];

    public function getIsFollowedByAuthUserAttribute()
    {
        return Follow::where('user_id', Auth::id())
            ->where('organizer_id', $this->mobile_user_id)
            ->exists();
    }


    public function mobileUser()
    {
        return $this->belongsTo(MobileUser::class);
    }

    public function categories()
    {
        return $this->belongsToMany(EventsCategory::class, 'organizer_categories' , 'organizer_id', 'category_id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'organizer_id');
    }

    public function albums()
    {
        return $this->hasMany(OrganizerAlbum::class);
    }
}

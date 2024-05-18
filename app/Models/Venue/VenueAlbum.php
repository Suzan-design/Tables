<?php

namespace App\Models\Venue;

use App\Models\ServiceProvider\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VenueAlbum extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function ($venueAlbum) {

            if ($venueAlbum->images) {
                $images = json_decode($venueAlbum->images, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            if ($venueAlbum->videos) {
                $videos = json_decode($venueAlbum->videos, true);
                if (is_array($videos)) {
                    foreach ($videos as $video) {
                        Storage::disk('public')->delete($video);
                    }
                }
            }
        });
    }

    protected $fillable = [
        'name',
        'videos',
        'images',
        'venue_id'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}

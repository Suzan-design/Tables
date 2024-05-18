<?php

namespace App\Models\ServiceProvider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceProvidersAlbums extends Model
{
    use HasFactory;
    protected $table= 'service_providers_albums' ;


    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function ($serviceProvidersAlbums) {

            if ($serviceProvidersAlbums->images) {
                $images = json_decode($serviceProvidersAlbums->images, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            if ($serviceProvidersAlbums->videos) {
                $videos = json_decode($serviceProvidersAlbums->videos, true);
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
        'service_provider_id'
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}

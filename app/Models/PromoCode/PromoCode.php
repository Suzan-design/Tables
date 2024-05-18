<?php

namespace App\Models\PromoCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PromoCode extends Model
{
    use HasFactory;

    protected $table = 'promo_codes' ;

    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function ($object) {
            // Check if the post has an image
            if ($object->image) {
                // Delete the image from storage
                Storage::disk('public')->delete($object->image);
            }
        });
    }

    protected $fillable = [
        'title',
        'description',
        'image' ,
        'code' ,
        'discount' ,
        'limit' ,
        'start-date' ,
        'end-date' ,
    ];

    public function events()
    {
        return $this->hasMany(EventPromoCode::class);
    }
    public function users()
    {
        return $this->hasMany(UserPromoCode::class) ;
    }
}

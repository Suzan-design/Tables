<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\images_offer;

class offer extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function images()
    {
        return $this->hasMany(images_offer::class,'imageable_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'Restaurant_id');
    }
    protected $casts = [
        'featured' => 'array', // Cast to array
        'ar_featured' => 'array',
    ];
}

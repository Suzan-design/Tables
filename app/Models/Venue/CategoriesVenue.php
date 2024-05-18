<?php

namespace App\Models\Venue;

use App\Models\Event\EventsCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesVenue extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'category_id'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function category()
    {
        return $this->belongsTo(EventsCategory::class, 'category_id');
    }
}

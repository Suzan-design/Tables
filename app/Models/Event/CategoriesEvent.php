<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesEvent extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'event_id'];

    public function category()
    {
        return $this->belongsTo(EventsCategory::class, 'category_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

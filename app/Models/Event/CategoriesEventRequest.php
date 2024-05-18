<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesEventRequest extends Model
{
    use HasFactory;

    protected $fillable = ['event_request', 'category_id'];

    public function eventRequest()
    {
        return $this->belongsTo(EventRequest::class, 'event_request');
    }

    public function category()
    {
        return $this->belongsTo(EventsCategory::class, 'category_id');
    }
}

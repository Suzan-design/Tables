<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id' ,
        'percent'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}

<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTrip extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'start_date','end_date' ,  'description' , 'description_ar'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    protected $hidden = [
        'created_at' ,
        'updated_at'
    ] ;
}

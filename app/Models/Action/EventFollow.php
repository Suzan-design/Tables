<?php

namespace App\Models\Action ;

use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFollow extends Model
{
    use HasFactory;

    protected $fillable= [
        'user_id' ,
        'event_id'
    ];
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}

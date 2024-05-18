<?php
namespace App\Models\Event;

use App\Http\Requests\Event\AdditionalRequestRequest;
use App\Models\Common\Interest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Framework\Attributes\Ticket;

class EventClass extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'code', 'ticket_price' , 'ticket_number'];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'class_interest');
    }

    public function event()
    {
        return $this->belongsTo(Event::class , 'event_id');
    }

    protected $hidden = [
        'created_at' ,
        'updated_at'
    ] ;

}

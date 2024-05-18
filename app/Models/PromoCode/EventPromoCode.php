<?php

namespace App\Models\PromoCode;

use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPromoCode extends Model
{
    use HasFactory;

    protected $table = 'event_promo_codes';

    protected $fillable = ['event_id' , 'promo_code_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

}

<?php

namespace App\Models\Event;

use App\Models\User\MobileUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledBooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'class_id',
        'first_name',
        'last_name',
        'age',
        'phone_number',
        'interest',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(MobileUser::class , 'user_id') ;
    }
    public function eventClass()
    {
        return $this->belongsTo(EventClass::class, 'class_id');
    }
}

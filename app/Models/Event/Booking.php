<?php

namespace App\Models\Event;

use App\Models\Event\EventClass;
use App\Models\User\MobileUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id' ,
        'interest' ,
        'phone_number',
        'age' ,
        'last_name'  ,
        'first_name'
    ];

    public function user()
    {
        return $this->belongsTo(MobileUser::class , 'user_id') ;
    }
    public function eventClass()
    {
        return $this->belongsTo(EventClass::class, 'class_id');
    }
    protected $hidden = [
        'created_at' ,
        'updated_at'
    ] ;
}



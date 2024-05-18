<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestEvent extends Model
{
    use HasFactory;

    protected $table = 'interest_event' ;

    protected $fillable = [
        'event_id' ,
        'interest_id' ,
        'price' ,
        'description' ,
        'description_ar'
    ] ;

}

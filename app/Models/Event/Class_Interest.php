<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_Interest extends Model
{
    use HasFactory;
    protected $table='class_interest' ;
    protected $fillable = [
        'event_class_id' ,
        'interest_id'
    ];
}

<?php

namespace App\Models\Action;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table='reviews' ;

    protected $fillable =[
        'user_id' ,
        'event_id'  ,
        'rate' ,
        'comment'
    ];


}

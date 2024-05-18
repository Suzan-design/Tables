<?php

namespace App\Models\Action;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable=[
        'title' ,
        'description' ,
        'user_id' ,
        'type'
    ] ;
}

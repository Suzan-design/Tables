<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable=[
        'title' ,
        'description' ,
        'ar_title' ,
        'ar_description' ,
        'customer_id' ,
        'date',
        'seen_type' ,
        'live_type'
    ] ;
}

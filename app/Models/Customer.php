<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    
    protected $guarded=[];
    protected $casts = [
        'followed_restaurants' => 'array',
        'coordinates' => 'array',
        'promocodes' => 'array',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'customer_id');
    }
    
    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class , 'customer_id') ;
    }
}

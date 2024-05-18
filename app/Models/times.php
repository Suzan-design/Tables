<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class times extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $table='restaurantoperatinghours';

    public function Restaurant()  //Restaurant
    {
        return $this->belongsToMany(Restaurant::class,'Restaurant_id');
    }
}

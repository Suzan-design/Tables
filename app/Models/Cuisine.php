<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;

class Cuisine extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function Restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
  
}

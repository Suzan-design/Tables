<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;

class restaurnats_categories extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function Restaurants()
    {
        return $this->hasMany(Restaurant::class,'category_id');
    }
}

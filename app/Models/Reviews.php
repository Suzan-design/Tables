<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Reviews extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $table='restaurantreviews';
    public function customer()  //Restaurant
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class DeviceToken extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id' ,
        'device_token'
    ] ;
    
    
    public function Customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Table;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Customer;
class Reservation extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $table='tablereservations';
    protected $casts = [
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
    ];
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function user()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class,'Restaurant_id');
    }

}

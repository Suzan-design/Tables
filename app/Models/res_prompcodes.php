<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Promocode;

class res_prompcodes extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table='restaurantpromoCodes';

    public function promocode()
    {
        return $this->belongsTo(Promocode::class,'promocode_id');
    }

}

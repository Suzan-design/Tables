<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Type;

class Menu extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $table='menuitems';
    public function type()
    {
        return $this->belongsTo(Type::class,'type_id','id');
    }
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
}

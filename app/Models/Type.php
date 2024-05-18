<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Type extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function menuitems()
    {
        return $this->hasMany(Menu::class,'type_id');
    }
}

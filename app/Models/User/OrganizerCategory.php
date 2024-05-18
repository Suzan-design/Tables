<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerCategory extends Model
{
    use HasFactory;
    protected $table =[
        'category_id' ,
        'organizer_id'
    ];

    public function organizers()
    {
        return $this->belongsToMany(Organizer::class, 'organizer_categories');
    }

}

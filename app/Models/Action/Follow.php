<?php

namespace App\Models\Action;

use App\Models\User\MobileUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $table='follows' ;

    protected $fillable=[
        'user_id'  ,
        'organizer_id'
    ];
    public function organizer()
    {
        return $this->belongsTo(MobileUser::class, 'organizer_id');
    }
}

<?php

namespace App\Models\Action;

use App\Models\Venue\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueReview extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id' ,
        'venue_id'  ,
        'rate' ,
        'comment'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class , 'venue_id') ;
    }
}

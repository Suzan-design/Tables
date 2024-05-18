<?php

namespace App\Models\Action;

use App\Models\Common\Reel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelLike extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'reel_id'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}

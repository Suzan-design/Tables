<?php

namespace App\Models\Action;

use App\Models\Common\Reel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelComment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'reel_id'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}

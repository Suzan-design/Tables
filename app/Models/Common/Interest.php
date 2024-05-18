<?php

namespace App\Models\Common;

use App\Models\User\UserInterest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Interest extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($object) {
            if ($object->icon) {
                Storage::disk('public')->delete($object->icon);
            }
        });
    }
    protected $table = 'interest' ;

    protected $fillable = [
        'icon' ,
        'title',
        'title_ar'
    ];

}

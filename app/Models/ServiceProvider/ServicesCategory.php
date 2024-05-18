<?php

namespace App\Models\ServiceProvider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServicesCategory extends Model
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

    protected $fillable = ['title_ar', 'title', 'icon' , 'description' , 'description_ar'];

    public function serviceProviders()
    {
        return $this->hasMany(ServiceProvider::class, 'category_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded=[''];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // Ensure we don't overwrite an existing external_id
            if (empty($invoice->external_id)) {
                $invoice->external_id = static::generateUniqueExternalId();
            }
        });
    }

    protected static function generateUniqueExternalId()
    {
        do {
            $randomId = random_int(1000000000, PHP_INT_MAX);
        }
        while (static::where('external_id', $randomId)->exists());

        return $randomId;
    }


    public function user()
    {
        $this->belongsTo(Customer::class , 'customer_id');
    }
    
}

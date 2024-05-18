<?php

namespace App\Models\PromoCode;

use App\Models\User\MobileUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPromoCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id' , 'promo_code_id'];

    public function event()
    {
        return $this->belongsTo(MobileUser::class , 'user_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class , 'promo_code_id');
    }

}

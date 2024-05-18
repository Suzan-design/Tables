<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerificationCode extends Model
{
    use HasFactory;
    protected $fillable = ['phone_number', 'otp', 'expire_at'];
}

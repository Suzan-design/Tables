<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    use HasFactory;
    protected $table = 'reset_code_passwords' ;
    protected $fillable = [
        'phone_number',
        'code'
    ];
}

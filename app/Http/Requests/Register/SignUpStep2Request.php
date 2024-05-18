<?php

namespace App\Http\Requests\Register;

use App\Http\Requests\ValidationFormRequest;

class SignUpStep2Request extends ValidationFormRequest
{
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/', // Must contain at least one letter and one number
            ],
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'state' => 'required|in:Aleppo,Al-Ḥasakah,Al-Qamishli,Al-Qunayṭirah,Al-Raqqah,Al-Suwayda,Damascus,Daraa,Dayr al-Zawr,Ḥamah,Homs,Idlib,Latakia,Rif Dimashq',
            'image' => 'required',
        ];
    }
}

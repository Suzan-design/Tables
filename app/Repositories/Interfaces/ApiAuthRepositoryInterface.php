<?php

namespace App\Repositories\Interfaces;

interface ApiAuthRepositoryInterface
{
    public function findByPhone($phone);
    public function updateOtp($customer, $otp);
    public function checkCredentials($phone, $password);
    public function generateUniqueCode();

  
}

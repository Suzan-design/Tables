<?php

namespace App\Repositories\Interfaces;

interface OfferRepositoryInterface
{
    public function getAllOffers($restaurantId);
    public function storeOffer($data);
}

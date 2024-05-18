<?php

namespace App\Repositories;

use App\Models\offer;
use App\Repositories\Interfaces\OfferRepositoryInterface;

class OfferRepository implements OfferRepositoryInterface
{
    public function getAllOffers($restaurantId)
    {
        return offer::where('Restaurant_id', $restaurantId)->with('images')->get();
    }

    public function storeOffer($data)
    {
        $offer = offer::create($data);
        return $offer;
    }
}

<?php

namespace App\Repositories;

use App\Models\Promocode;
use App\Models\res_prompcodes;
use App\Repositories\Interfaces\PromocodeRepositoryInterface;

class PromocodeRepository implements PromocodeRepositoryInterface
{
    public function getAllPromocodes()
    {
        return Promocode::where('type', 'promotion')->get();
    }

    public function storePromocode($data, $filteredUsers, $filteredRestaurants)
    {
        $promocode = Promocode::create($data);
        $insertData = $filteredRestaurants->pluck('id')->map(function ($restaurantId) use ($promocode) {
            return [
                'restaurant_id' => $restaurantId,
                'promocode_id' => $promocode->id,
            ];
        })->all();
        res_prompcodes::insert($insertData);
        return $promocode;
    }

    public function findPromocodeById($id)
    {
        return Promocode::findOrFail($id);
    }

    public function deletePromocode($id)
    {
        $promocode = $this->findPromocodeById($id);
        $promocode->delete();
    }

    public function updatePromocodeStatus($id, $status)
    {
        $promocode = $this->findPromocodeById($id);
        $promocode->update(['status' => $status]);
    }
}

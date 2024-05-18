<?php

namespace App\Repositories\Interfaces;

interface PromocodeRepositoryInterface
{
    public function getAllPromocodes();
    public function storePromocode($data, $filteredUsers, $filteredRestaurants);
    public function findPromocodeById($id);
    public function deletePromocode($id);
    public function updatePromocodeStatus($id, $status);
}

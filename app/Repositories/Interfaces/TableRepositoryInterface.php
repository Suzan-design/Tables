<?php

namespace App\Repositories\Interfaces;

interface TableRepositoryInterface
{
    public function getTablesWithReservations($id, $date);
    public function getAllTables($restaurantId);
    public function findTableById($id);
    public function createTable($data);
    public function updateTable($id, $data);
    public function deleteTable($id);
}
